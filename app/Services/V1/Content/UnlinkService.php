<?php

namespace App\Services\V1\Content;

use App\Models\modelsField;
use App\Models\Models;
use App\Models\ModelsItemsBind;
//use App\Models\instCard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Services\V1\Fields\FieldsService;


/*
 * 
 */
class UnlinkService extends ContentService {

	public function __construct(){
		
	}

	public function inlinkItem($request){

        $mode = $request->mode;

        return call_user_func([$this, $mode], $request);

    }

    public function unlink($request){
        
        $item = (array)DB::table($request->parent_model)->where('id', $request->parent_item_id)->first();

        $field_name = strripos($request->field_name, $request->parent_model.'_child_') !== false ? 
                        str_replace($request->parent_model.'_child_', '', $request->field_name) : 
                        $request->field_name;

        $list = explode(',', $item[$field_name]);

        $key = array_search($request->child_item_id, $list);

        if ($key !== false){
            unset($list[$key]);
        }

        DB::table($request->parent_model)->where('id', $request->parent_item_id)->update([$field_name=>implode(',', $list)]);

        return $list;
    }

    public function list($request){
        
       return $this->unlink($request);

    }

    public function child($request){

        $params = [
            'parent_model' =>  $request->child_model, 
            'child_model' =>  $request->parent_model, 
            'parent_item_id' =>  $request->child_item_id, 
            'child_item_id' =>  $request->parent_item_id,
            'field_name' =>  $request->field_name,
            'field_id' =>  $request->field_id
        ];
        
        return $this->parent((object)$params);
 
    }

    public function parent($request, $delete_child = true){

        ModelsItemsBind::where('parent_model', $request->parent_model)->
                         where('child_model', $request->child_model)->
                         where('parent_item_id',  $request->parent_item_id)->
                         where(function($query) use($request){
                            $query->orWhere('child_field_id',  $request->field_id)->
                            orWhere('parent_field_id',  $request->field_id);
                         })->
                         where('child_item_id', $request->child_item_id)->delete();

        if ($delete_child){
            
            $params = [
                'parent_model' =>  $request->child_model, 
				'child_model' =>  $request->parent_model, 
				'parent_item_id' =>  $request->child_item_id, 
				'child_item_id' =>  $request->parent_item_id,
                'field_name' =>  str_replace($request->parent_model.'_child_', '', $request->field_name),
                'field_id' =>  $request->field_id
            ];

            return $this->unlink((object)$params);
        }

        return true;

    }
	
}