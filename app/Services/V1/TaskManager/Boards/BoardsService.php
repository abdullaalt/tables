<?php

namespace App\Services\V1\TaskManager\Boards;

use App\Models\Board;
use App\Models\BoardsBind;
//use App\Models\instCard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\TaskManager;

use App\Services\V1\Content\ContentService;
use App\Http\Resources\V1\ContentListResource;

/*
 * 
 */
class BoardsService{

    public function getBoardsList(){

        return $this->getBoardsList();

    }

    public function getUserBoardsList($user_id = false){

        $user_id = !$user_id ? Auth::id() : $user_id;

        $boards = TaskManager::getInstance()->getUserBoards($user_id)->fillOwners()->get();

        return $boards;

    }
    
}