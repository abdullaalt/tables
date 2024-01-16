<?php

namespace App\Services\V1\Users;

use App\Models\Models;
use App\Models\modelsField;
use App\Models\usersGroup;
//use App\Models\instCard;
use Illuminate\Support\Facades\Auth;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Services\V1\Users\UsersService;

use App\Http\Resources\V1\GroupsResource;

/*
 * 
 */
class GroupsService extends UsersService{

    public function getGroups(){
        
        return GroupsResource::collection(usersGroup::all());

    }

    public function getUserGroup($user_id = false){

        $user_id = $user_id ? $user_id : Auth::id();
        $user = $this->getUser($user_id);
        
        return usersGroup::find($user['user']->resource['group_id']);

    }

}