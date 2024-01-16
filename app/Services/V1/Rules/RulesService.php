<?php

namespace App\Services\V1\Rules;

use App\Models\Models;
use App\Models\modelsField;
use App\Models\Rule;
use App\Models\groupsRule;
//use App\Models\instCard;
use Illuminate\Support\Facades\Auth;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Services\V1\Users\GroupsService;
use App\Services\V1\Rules\PermissionsService;

use App\Http\Resources\V1\RulesResource;

/*
 * 
 */
class RulesService extends GroupsService{

    public function getRules(){
        
        return RulesResource::collection(Rule::all());

    }

    public function saveRules($request){

        if (!Auth::user()->is_admin) return response()->json(['errors'=>['Нет прав']], 403);

        $item = $request->all();
       
        return self::save($item);

    }

    static function save($item){

        $item['rules'] = json_encode($item['rules']);

        return groupsRule::saveRules($item);

    }

}