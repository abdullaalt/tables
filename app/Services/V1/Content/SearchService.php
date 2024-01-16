<?php

namespace App\Services\V1\Content;

use App\Models\modelsField;
use App\Models\Models;
//use App\Models\instCard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Services\V1\Fields\FieldsService;
use App\Services\V1\Content\ContentService;


/*
 * 
 */
class SearchService extends ContentService {

    function search($request){

        $model = $request->model;

        $fs = new FieldsService();
		
        $items = DB::table($model);
        $filters = json_decode($request->filters);
        foreach ($filters as $filter){
            $field = $fs->getModelFieldByName($model, $filter->by_field);
            $items = $field->handler->search((object)$filter, $items);
        }
        
        return $this->getData($model, $items);

    }

}