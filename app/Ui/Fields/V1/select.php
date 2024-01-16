<?php

namespace App\Ui\Fields\V1;

use App\Models\modelsField;
use App\Models\Models;
use App\Models\ModelsItemsBind;
use App\Models\modelsFieldsBind;
//use App\Models\instCard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Fields;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Services\V1\Fields\FieldsService;
use App\Http\Resources\V1\ContentListResource;

/*
 * 
 */
class select extends Fields {

	protected $options;

	public function __construct($field){

		$this->options = $field;

	}

	public function options(){
		return $this->options;
	}

	public function getValue($value, $model, $field, $item = false){

		if ($field->is_connect){
			$ids = explode(',', $value);
			$list = DB::table($field->connect_model)->whereIn('id', $ids)->select('id', 'title')->get();
		}else{
			$values = explode(",", $value);
			$list = [];
			foreach ($values as $v){
				$list[] = (object)[
					'id' => $v,
					'title' => $v
				];
			}
			$list = $list;
		}
		//dd($list);
        return ContentListResource::collection($list);

	}

	public function store($value){

		$value = is_array($value) ? implode(',', $value) : $value;

        return $value;

    }

	public function bind($value, $id, $old_value = false){//родитель, сын
		if (is_array($value)) return true;
		if (empty(trim($value))) return true;
		if (!$this->options()->multiple && $this->options()->connect_model && $this->options()->parent_field_id){
			ModelsItemsBind::create([
				'parent_model' => $this->options()->connect_model, 
				'child_model' => $this->options()->model, 
				'parent_item_id' => $value, 
				'child_item_id' => $id,
				'parent_field_id' => $this->options()->parent_field_id,
				'child_field_id' => $this->options()->id,
			]);

			if ($old_value){

				ModelsItemsBind::where('parent_model', $this->options()->connect_model)->
								where('child_model', $this->options()->model)->
								where('parent_item_id',  $old_value)->
								where('child_field_id',  $this->options()->id)->
								where('parent_field_id',  $this->options()->parent_field_id)->
								where('child_item_id', $id)->
								delete();

			}
		}
	}

    public function edit($item){
        
		return true;

    }

	public function search($request, $descriptor){

		if ($this->options()->is_connect){

			$items = DB::table($this->options()->connect_model)->
						where('title', 'like', '%'.$request->value.'%')->get();

			$ids = [];

			foreach ($items as $key => $item) {
				$ids[] = $item->id;
			}

			$descriptor->whereIn($this->options()->name, $ids);

		}else{

			$descriptor->where($request->by_field, 'like', '%'.$request->value.'%');

		}
        
		return $descriptor;

    }

	public function delete(){

		if ($this->options()->parent_field_id){
			$fs = new FieldsService();
			$fs->deleteField($this->options()->parent_field_id);
			modelsFieldsBind::deleteBindFields($this->options()->id, $this->options()->parent_field_id);
			ModelsItemsBind::deleteBindItems($this->options()->id, $this->options()->parent_field_id);
		}
		
		return true;

	}
	
}