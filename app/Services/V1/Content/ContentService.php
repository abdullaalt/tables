<?php

namespace App\Services\V1\Content;

use App\Models\modelsField;
use App\Models\Models;
//use App\Models\instCard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Services\V1\Fields\FieldsService;
use App\Services\V1\Rules\RulesService;


/*
 * 
 */
class ContentService {

	public function __construct(){
		
	}

	public function getModelByName($model){
		return Models::where('name', $model)->first();
	}

	public function gedItem($model, $item_id){

		$model = Models::where('name', $model)->first();

		$fs = new FieldsService();
		$fields = $fs->getModelFields($model->name);

		if (count($fields) < 1){
			return response()->json(['errors'=>['Ошибка доступа']], 403);
		}

		$item = DB::table($model->name)->where('id', $item_id)->first();

	}

	public function renderItem($item, $fields, $model){

		$result = [];

		foreach ($item as $key1 => $field) {
			if (!isset($fields[$key1])) continue;

			$value = $fields[$key1]->handler->getValue($field, $model->name, $fields[$key1], $item);
			
			$result[$key1] = [
				'field_title' => $fields[$key1]->title,
				'value' => $value,
				'name' => $key1,
				'type' => @$fields[$key1]['type']
			];
		}

		return $result;

	}
	
	public function getData($model, $items = false){
		
        $model = Models::where('name', $model)->first();

		$fs = new FieldsService();
		$fields = $fs->getModelFields($model->name);

		if (count($fields) < 1){
			return response()->json(['errors'=>['Ошибка доступа']], 403);
		}

		if ($items){
			$items = $items->
					orderBy('id', 'desc')->simplepaginate(200)->toArray();
		}else{
			$items = DB::table($model->name)->orderBy('id', 'desc')->simplepaginate(30)->toArray();
		}

		$result = [];

		foreach ($items['data'] as $key=> $item){
			$result[$key] = $this->renderItem($item, $fields, $model);
		}
		$items['data'] = $result;
		return $items;
	}
	

	public function saveData($model, $request){

		$model = Models::where('name', $model)->first();
		$fs = new FieldsService();
		$fields = $fs->getModelFields($model->name);
		
		$items = $request->all();

		$data = [];

		foreach ($items as $key => $item) {
			if (!isset($fields[$key])) continue;
	
			$data[$key] = $fields[$key]->handler->store($item);
		}
		//dd($data);
		$id = DB::table($model->name)->insertGetId($data);

		foreach ($items as $key => $item) {
			if (!isset($fields[$key])) continue;
	
			$data[$key] = $fields[$key]->handler->bind($item, $id);//родитель, сын
		}

		$item = $this->getItem($id, $model);

		return $item;

	}

	public function getItem($id, $model)
	{

		$fs = new FieldsService();
		$fields = $fs->getModelFields($model->name);

		$item = DB::table($model->name)->where('id', $id)->first();

		$result = [];

		foreach ($item as $key1 => $field) {
			if (!isset($fields[$key1])) continue;

			$value = $fields[$key1]->handler->getValue($field, $model->name, $fields[$key1]);

			$result[$key1] = [
				'value' => $value,
				'name' => $key1,
				'type' => @$fields[$key1]['type']
			];
		}

		return $result;
	}

	public function saveCell($request){

		$fs = new FieldsService();
		$field = $fs->getField($request->field_id);
		$item = (array)DB::table($field->model)->where('id', $request->item_id)->first();

		$value = $field->handler->store($request->value);

		$field->handler->bind($request->value, $request->item_id, $item[$field->name]);

		DB::table($field->model)->where('id', $request->item_id)->update([$field->name=>$value]);

		return ['value'=>$field->handler->getValue($value, $field->model, $field)];

	}
	
}