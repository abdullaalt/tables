<?php

namespace App\Services\V1\Models;

use App\Models\Models;
use App\Models\modelsField;
//use App\Models\instCard;
use Illuminate\Support\Facades\Auth;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Services\V1\Rules\PermissionsService;

/*
 * 
 */
class ModelsService {

	protected $default_fields = [
		[
			'title' => 'ID',
			'name' => 'id',
			'is_require' => 1,
			'in_filter' => 1,
			'is_show' => 1,
			'type' => 'varchar',
			'can_delete' => 0,
			'fillable' => 0,
			'can_edit' => 0,
		],

		[
			'title' => 'Название',
			'name' => 'title',
			'is_require' => 1,
			'in_filter' => 1,
			'is_show' => 1,
			'type' => 'varchar',
			'can_delete' => 0,
			'can_edit' => 1,
		]
	];

	protected $permissions_service;

	public function __construct(){
		$this->permissions_service = new PermissionsService();
	}
	
	public function models($is_public = true){

		if ($is_public){
			$items = $this->permissions_service->filter(Models::where('is_public', 1), 'model')->get();
		}else{
			$items = $this->permissions_service->filter(Models::where('id', '>', 0), 'model')->get();
		}
		
		return $items;
	}

	public function saveModel($request){
		
		$model_id = $request->has('model_id') ? $request->model_id : -1;

		$item =  $request->all();

		$item['is_public'] = $request->has('is_public');
		$item['in_menu'] = $request->has('in_menu');

		$model = Models::updateOrCreate(['id'=>$model_id], $item);

		if (!$request->has('model_id')){
			$this->createModel($request);
			$this->addFields($request);
		}

		savePermissions($request, [
			'source' => 'model',
			'source_id' => $model->id
		]);

		return $model->is_public ? $model : [];

	}

	public function createModel($request){
		Schema::create($request->name, function (Blueprint $table) {
			$table->id();
			$table->string('title', 255);
        });
	}

	public function addFields($request){
		foreach($this->default_fields as $field){
			$field['model'] = $request->name;
			modelsField::create($field);
		}
	}
	
}