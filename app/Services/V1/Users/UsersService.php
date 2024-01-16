<?php

namespace App\Services\V1\Users;

use App\Models\Models;
use App\Models\modelsField;
use App\Models\usersModelsBind;
use App\Models\User;
//use App\Models\instCard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Http\Resources\V1\UserResource;

use App\Services\V1\Users\GroupsService;
use App\Services\V1\Rules\RulesService;

/*
 * 
 */
class UsersService{

    protected function generatePassword($password){
        return Hash::make($password);
    }

    protected function createUser($user){
        $user = User::create($user);
        return $user->id;
    }

    public function isAdmin($user_id = false){

        $user_id = $user_id ? $user_id : Auth::id();
        //$group = 

    }

    public function getUser($user_id = false, $get_admin_data = true){

        $user_id = $user_id ? $user_id : Auth::id();
        $result = [];
        $result['user'] = new UserResource(User::getUser($user_id));

        if ($result['user']->resource['is_admin'] && $get_admin_data){
            $gs = new GroupsService();
            $result['groups'] = $gs->getGroups();

            $rs = new RulesService();
            $result['rules'] =  $rs->getRules();
        }
        return $result;

    }

    protected function auth($user_id){
        $user = User::where('id', $user_id)->first();
        $result = [
			'token' => $user->createToken('gos')->plainTextToken,
            'user' => $this->getUser($user_id)
        ];
        return $result;
    }

}