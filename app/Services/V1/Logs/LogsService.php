<?php

namespace App\Services\V1\Logs;

use App\Models\Log;
// use App\Models\modelsFieldsBind;
// use App\Models\Models;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\DB;

use App\Services\V1\Logs\LogsMethodsService;
// use App\Services\V1\Rules\PermissionsService;
// use App\Http\Resources\V1\ContentListResource;

/*
 * 
 */
class LogsService extends LogsMethodsService{

    protected $controllers = [
        'fields',
        'models',
        'unlink',
        'cell',
        'search',
        'groups',
        'rules',
        'user',
        'permissions',
        'content'
    ];

    public function __construct($request){
        $this->path = explode('/', $request->path());
        $this->item = [];
    }

    public function addValue($key, $value){

        $this->item[$key] = $value;

    }

    protected function getСontroller(){
        $controller = false;
        foreach ($this->path as $key => $value) {
            if (array_search($value, $this->controllers) !== false){
                $controller = $value;
                break;
            }
        }

        if (!$controller){

        }

        return $controller;
    }

    protected function getDataForController($controller, $func_siff){

        $action = '';
        if (!$controller){
            return '';
        }

        if (method_exists($this, $controller.$func_siff)){
            $action = call_user_func([$this, $controller.$func_siff], $controller);
        }else{
            return '';
        }

        return $action;

    }

    public function register($request, $response){

        $this->addValue('user_id', Auth::id());
        
        call_user_func([$this, strtolower($request->method()).'Init'], $request, $response);

    }

    protected function getData(){

        $controller = $this->getСontroller();
        $action = $this->getDataForController($controller, 'Action');
        $params = $this->getDataForController($controller, 'Params');
        
        $this->addValue('controller', $controller);
        $this->addValue('action', $action);
        if (is_array($params) && count($params) > 0){
            $this->addValue('params', json_encode($params));
        }

    }

    protected function getInit($request, $response){

        $this->addValue('event', 'read');
        $this->getData();

        $this->addLog();

    }

    protected function postInit($request, $response){

        $this->addValue('event', $request->action);
        $this->getData();

        $data = json_encode($request->all());
        $this->addValue('data', $data);
        $this->addValue('response', $response->content());

        $this->addLog();

    }

    private function addLog(){//dd($this->item);
        Log::create($this->item);
    }

}