<?php

namespace App\Services\V1\Fields;

use App\Models\modelsField;
use App\Models\modelsFieldsBind;
use App\Models\Models;
//use App\Models\instCard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Services\V1\Content\ContentService;
use App\Services\V1\Rules\PermissionsService;
use App\Http\Resources\V1\ContentListResource;

use App\Ui\Fields\V1\select;
use App\Ui\Fields\V1\text;
use App\Ui\Fields\V1\varchar;
use App\Ui\Fields\V1\parent_id;

/*
 * 
 */
class FieldsService{

	public $cs;

	protected $permissions_service;

	public function __construct(){
		$this->cs = new ContentService();
		$this->permissions_service = new PermissionsService();

		return $this;
	}
	
	public function fields($request){

		$items = $this->getModelFields($request->model);

		$result = [];

		foreach ($items as $item){

			if ($item->is_connect && $item->connect_model){
				$item->connect_model_list = ContentListResource::collection($this->getFieldConnectModelList($item));
			}else{
				if ($item->type == 'list'){
					$list = explode("\n", $item->items);
					$r = [];
					foreach ($list as $key => $l) {
						$r[] = (object)[
							'id'=>$l,
							'title'=> $l
						];
					}

					$item->connect_model_list = ContentListResource::collection($r);
				}
			}

			$result[$item->id] = $item;

		}
		
		return $result;
	}

	public function getModelFields($model){

		$fields = $this->permissions_service->filter(
				modelsField::where('model', $model)->
					leftJoin('models_fields_bind', 'models_fields_bind.parent_field_id', '=', 'models_fields.id')->
					leftJoin('models_fields_bind as m', 'm.child_field_id', '=', 'models_fields.id')->
					select('models_fields.*', 'models_fields_bind.child_field_id', 'm.parent_field_id'), 'field'
				)->orWhere(function($q) use($model){
					$q->where(function($query){
						$query->where('name', 'id')->orWhere('name', 'title');
					})->where('model', $model);
				})->				
				get();
		$result = [];

		foreach ($fields as $key => $field) {
			$field->handler = $this->getFieldHandler($field);
			$result[$field->name] = $field;
		}

		return $result;

	}

	public function getFieldHandler($field){
		
		$name = $field->type == 'list' ? 'select' : $field->type;
		$class_name = "App\Ui\Fields\V1\\".$name;
		$handler = new $class_name($field);

		return $handler;
	}

	public function editField($mode, $item, $request){
		
		$field = $this->getField($request->field_id);

		modelsField::find($request->field_id)->fill($item)->save();

		$field->handler->edit($item);

		return $this->getField($request->field_id);
	
	}

	public function addFieldInModel($model, $request){

		$item = $request->all();

		$item['model'] = $model;

		$item['is_require'] = $request->has('is_require');
		$item['in_filter'] = $request->has('in_filter');
		$item['is_show'] = $request->has('is_show');
		$item['multiple'] = $request->has('multiple');

		if (isset($item['connect_model']) && $item['connect_model']){
			$item['is_connect'] = true;
		}else if (isset($item['connect_model']) && !$item['connect_model']){
			$item['is_connect'] = false;
			$item['connect_model'] = NULL;
		}
		$item['items'] = $request->has('list') ? @$item['list'] : @$item['items'];
		if ($request->has('field_id')) return $this->editField($model, $item, $request);
		
		$this->addField($model, $request->type, $request->name);
		
        $item = modelsField::create($item);

		if (!$item['multiple'] && $item['type'] == 'list' && $item['is_connect']){

			$m = $this->cs->getModelByName($model);

			$parent = [
				'title' => $m->title,
				'name' => $model.'_child_'.$request->name,
				'model' => $item['connect_model'],
				'is_require' => $item['is_require'],
				'is_show' => 0,
				'in_filter' => $item['in_filter'],
				'type' => 'parent_id',
				'is_connect' => 1,
				'connect_model' => $model,
				'can_delete' => 0,
				'fillable' => 0,
				'can_edit' => 0
			];

			$this->addField($item['connect_model'], 'varchar', $model.'_child_'.$request->name);

			$parent = modelsField::create($parent);

			modelsFieldsBind::create([
				'parent_field_id' => $parent->id,
				'child_field_id' => $item->id
			]);

		}

		return $this->getField($item->id);

    }

	public function addField($model, $type, $name){
		Schema::table($model, function (Blueprint $table) use($type, $name) {
			if ($type == 'checkbox'){
				$table->integer($name)->nullable($value = true);
			}else if ($type == 'list'){
				$table->text($name)->nullable($value = true);
			}else if ($type == 'varchar'){
				$table->string($name, 255)->nullable($value = true);
			}else if ($type == 'text'){
				$table->text($name)->nullable($value = true);
			}
            
        });
	}

	public function getField($field_id){

		$item = modelsField::where('models_fields.id', $field_id)->
				leftJoin('models_fields_bind', 'models_fields_bind.parent_field_id', '=', 'models_fields.id')->
				leftJoin('models_fields_bind as m', 'm.child_field_id', '=', 'models_fields.id')->
				select('models_fields.*', 'models_fields_bind.child_field_id', 'm.parent_field_id')->
				first();

		if (!$item){
			return false;
		}

		if ($item->is_connect && $item->connect_model){
			$item->connect_model_list = ContentListResource::collection($this->getFieldConnectModelList($item));
		}
		
		$item->handler = $this->getFieldHandler($item);

		return $item;

	}

	public function getModelFieldByName($model, $name){
		$item = modelsField::where('models_fields.name', $name)->where('models_fields.model', $model)->
				leftJoin('models_fields_bind', 'models_fields_bind.parent_field_id', '=', 'models_fields.id')->
				leftJoin('models_fields_bind as m', 'm.child_field_id', '=', 'models_fields.id')->
				select('models_fields.*', 'models_fields_bind.child_field_id', 'm.parent_field_id')->
				first();

		if ($item->is_connect && $item->connect_model){
			$item->connect_model_list = ContentListResource::collection($this->getFieldConnectModelList($item));
		}
		
		$item->handler = $this->getFieldHandler($item);

		return $item;
	}

	public function getFieldConnectList($field_id){

		$field = $this->getField($field_id);

		if (!$field) return [];
		
		$list = [];

		if ($field->is_connect && $field->connect_model){
			$list = $this->getFieldConnectModelList($field);
		}

		return $list;

	}

	public function getFieldConnectModelList($field){
		return $list = DB::table($field->connect_model)->select('id', 'title')->get();
	}

	public function setFieldStatus($field_id, $status){

		return modelsField::where('id', $field_id)->update(['is_show'=>$status]);

	}

	public function deleteField($field_id){

		$field = $this->getField($field_id);
		if (!$field){
			return true;
		}
		$field->handler->delete();

		$this->deleteFieldFromTable($field->handler->options()->model, $field->handler->options()->name);
		modelsField::find($field_id)->delete();

		return true;

	}

	public function deleteFieldFromTable($model, $name){
		Schema::table($model, function (Blueprint $table) use($name) {
			$table->dropColumn($name);
		});
	}
	
}