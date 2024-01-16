<?php

namespace App\Services\V1\Rules;

use App\Models\Models;
use App\Models\modelsField;
use App\Models\Rule;
use App\Models\usersGroup;
use App\Models\groupsRule;

use Illuminate\Support\Facades\Auth;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Services\V1\Rules\RulesService;

use App\Http\Resources\V1\RulesResource;
use App\Http\Resources\V1\ModelsResource;

/*
 * 
 */
class PermissionsService extends RulesService{

    protected $user_permissions;

    public function __construct(){
        $this->user_permissions = $this->getUserPermissions();
    }

    public function getUserPermissions($user_id = false){

        $is_current_user = $user_id == Auth::id();

        $user_id = !$user_id ? Auth::id() : $user_id;

        $group_id = $is_current_user ? Auth::user()->group_id : $this->getUserGroup($user_id)->id;

        $group_permissions = groupsRule::getGroupPermissions($group_id);
        $permissions = [];
        $cache = [];

        foreach ($group_permissions as $key => $value) {
            //dd($value);
            $value['rules'] = json_decode($value['rules']);
            $value['type'] = $value['source'];
            $value['source'] = call_user_func([$this, $value['source'].'Source'], $value['source_id']);
            $value['permissions'] = [];
            
            $rules_ids = $value['rules'];

            foreach ($rules_ids as $key=>$id){
                if (isset($cache[$id])){
                    $value['permissions'][] = $cache[$id];
                    unset($rules_ids[$key]);
                }
            }

            if (count($rules_ids) > 0){
                $rules = Rule::whereIn('id', $rules_ids)->get();
                foreach ($rules as $rule){
                    $cache[$rule->id] = new RulesResource($rule);
                    $value['permissions'][] = $cache[$rule->id];
                }
            }

            $permissions[] = $value;
        }

        return $permissions;

    }

    protected function modelSource($source_id){

        return new ModelsResource(Models::where('id', $source_id)->first());

    }

    protected function searchSource($source_id){

        return [];

    }

    public function filter($model, $type){

        if (Auth::user()->is_admin) return $model;

        $ids = [];

        //dd($this->user_permissions);

        foreach ($this->user_permissions as $permission){

            if ($permission['type'] == $type) $ids[] = $permission['source_id'];

        }

        $model->whereIn('id', $ids);

        return $model;

    }

    public function hasAccess($permission_name, $type, $source_id = false, $request){

        if (Auth::user()->is_admin) return true;

        $access = false;
       
        if ($source_id == 'url'){
            $source_id = call_user_func([$this, 'get'.$type.'SourceId'], $request);
        }

        $type = $type == 'search' ? 'model' : $type;
        
        foreach ($this->user_permissions as $permission){
           
            if ($permission['type'] == $type && (($source_id && $permission['source_id'] == $source_id) || !$source_id)) {
                $access = $this->searchPermission($permission['permissions'], $permission_name);
            }

            if ($access){
                break;
            }

        }

        return $access;

    }

    protected function searchPermission($permissions, $permission_name){
        foreach ($permissions as $key => $rule) {
            if ($rule['name'] == $permission_name) {
                return true;
            }
        }
    }

    protected function getmodelSourceId($request){

        $path = explode('/', $request->path());

        $model = !empty(trim($path[count($path)-1])) ? $path[count($path)-1] : $path[count($path)-2];
        if (is_numeric($model)){
            $model = $path[count($path)-2];
        }

        $model = Models::where('name', $model)->first();

        return $model->id;

    }

    protected function getsearchSourceId($request){
        
        $model = Models::where('name', $request->model)->first();
        
        return $model->id;

    }

    protected function getitemSourceId($request){
        
        return $request->item_id;

    }

}