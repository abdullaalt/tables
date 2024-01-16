<?php

namespace App\Services\V1\Logs;

// use App\Models\Log;
// use App\Models\modelsFieldsBind;
use App\Models\Models;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\DB;

// use App\Services\V1\Content\ContentService;
// use App\Services\V1\Rules\PermissionsService;
// use App\Http\Resources\V1\ContentListResource;

/*
 * 
 */
class LogsMethodsService{

    protected $path;
    protected $item;

    public function contentAction($controller){

        $action = '';
        $key = array_search($controller, $this->path);

        if ($this->path[$key+1] == 'fields'){
            $action = 'field_list';
        }else{
            $action = 'data';
            //$action = Models::getModelField('name', $this->path[$key+1], 'id');
        }

        return $action;

    }

    public function contentParams($controller){

        $params = [];
        $key = array_search($controller, $this->path);
        
        if ($this->path[$key+1] == 'fields'){
            for ($i=$key+2; $i<count($this->path); $i++){
                $params[] = $this->path[$i];
            }
        }else{
            $params[] = Models::getModelField('name', $this->path[$key+1], 'id');
        }

        return $params;

    }

    public function fieldsAction($controller){
        
        $action = '';
        $key = array_search($controller, $this->path);

        if (!isset($this->path[$key+1])){
            $action = 'model_fields_list';
        }

        return $action;

    }

}