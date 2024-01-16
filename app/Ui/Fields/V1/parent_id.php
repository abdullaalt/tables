<?php

namespace App\Ui\Fields\V1;

use App\Models\modelsField;
use App\Models\Models;
use App\Models\ModelsItemsBind;
//use App\Models\instCard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Fields;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Services\V1\Content\ContentService;
use App\Http\Resources\V1\ContentListResource;

/*
 * 
 */
class parent_id extends Fields {

	protected $options;

	public function __construct($field){

		$this->options = $field;

	}
	public function getValue($value, $model, $field, $item = false){
		
		if ($item){
			$items = ModelsItemsBind::getBindsItems($field, $item);
		
        	return ContentListResource::collection($items);
		}else{
			return null;
		}
		

	}

	public function store($value){

		$value = is_array($value) ? implode(',', $value) : $value;

        return $value;

    }

	public function bind($value, $id, $old_value = false){//родитель, сын
		if (!$this->options()->multiple && $this->options()->connect_model){
			ModelsItemsBind::create([
				'parent_model' => $this->options()->connect_model, 
				'child_model' => $this->options()->model, 
				'parent_item_id' => $value, 
				'child_item_id' => $id
			]);
		}
	}

    public function edit($item){
        return true;
    } 
	
}