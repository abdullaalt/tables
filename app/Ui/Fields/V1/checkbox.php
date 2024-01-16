<?php

namespace App\Ui\Fields\V1;

use App\Models\modelsField;
use App\Models\Models;
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
class checkbox extends Fields {

    protected $options;

	public function __construct($field){

		$this->options = $field;

	}

    public function store($value){

        return $value == 'true' ? 1 : 0;

    }

    public function delete(){
        return true;
    }
	
}