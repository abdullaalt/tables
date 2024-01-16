<?php

namespace App\Services\V1\Users;

use App\Models\Models;
use App\Models\modelsField;
use App\Models\usersGosuslugi;
use App\Models\User;
//use App\Models\instCard;
use Illuminate\Support\Facades\Auth;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Http\Resources\V1\UserResource;

use App\Services\V1\Users\UsersService;
use App\Services\V1\Rules\RulesService;

/*
 * 
 */
class GosuslugiService extends UsersService{

    private $secret = '_SaRxl0SiF1GjDBdkaBMCF9fqJRvvd8wSFHhG2z9dYI';
    private $client_id = 'SMg-polcb0Ic39oAHhs9xDCtYY1dhSHaZMkYLyIGtE0';

    protected function hasUserByUID($uid){

        return usersGosuslugi::where('uid', $uid)->exists();

    }

    protected function getUserByUID($uid){

        return usersGosuslugi::where('uid', $uid)->value('user_id');

    }

    public function init($request){
        
        if ($request->has("error_description")){
           
            return $request->error_description;
        }else if ($request->has("access_token")){
            $this->getUserInfo($request);
        }else if ($request->has("code")){
            return $this->getAccessToken($request);
        }else{
            // if ($request->has("error_description")){
            //     return $request->error_description;
            // }
            $this->authUser($request);
        }

    }

    public function getUserInfo($data){
        
        if (!isset($data->access_token)){
            return $data->error_description;
        }
        $params = [
            'access_token='.$data->access_token
        ];

        $g_user_info = $this->sendRequest($params, 'https://futurecode.p.rnds.pro/auth/userinfo');
        dd($g_user_info);
        if ($this->hasUserByUID($g_user_info->info->uid)){
            $result = $this->auth($this->getUserByUID($g_user_info->info->uid));
        }else{
            $g_user = [
                'uid' => $g_user_info->info->uid
            ];

            $user = [
                'nickname' => $g_user_info->info->lastName.' '.$g_user_info->info->firstName.' '.$g_user_info->info->middleName,
                'email' => 'test'.$g_user_info->info->uid.'@gosuslugi.ru',
                'phone' => '+7'.$g_user_info->info->uid,
                'is_admin' => 0,
                'group_id' => 5,
                'password' => $this->generatePassword('pass_'.$g_user_info->info->uid)
            ];

            $g_user['user_id'] = $this->createUser($user);
            usersGosuslugi::create($g_user);
            $result = $this->auth($g_user['user_id']);
        }

        return $result;

    }

    public function getAccessToken($request){
       
        $params = [
            'grant_type=authorization_code',
            'client_id='.$this->client_id,
            'code='.$request->code,
            'client_secret='.$this->secret,
            'redirect_uri=https://crm.kod06.ru/auth/gosuslugi'
        ];		
        
        $data = $this->sendRequest($params);
       
        return $this->getUserInfo($data);
    }

    protected function sendRequest($params, $url = 'https://futurecode.p.rnds.pro/auth/token'){
        $ch = curl_init($url.'?'.implode('&', $params));
        curl_setopt($ch, CURLOPT_POST, 1);
        //curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($array, '', '&'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $html = curl_exec($ch);
        curl_close($ch);	
        //dd($ch);
        return json_decode($html);
    }
}