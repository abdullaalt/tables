<?php

namespace App\Ui\Fields\V1;

use App\Models\modelsField;
use App\Models\Models;
//use App\Models\instCard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Fields;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Services\V1\Content\ContentService;
use App\Http\Resources\V1\ContentListResource;

/*
 * 
 */
class varchar extends Fields {
	
    protected $options;

	public function __construct($field){

		$this->options = $field;

	}
    
}