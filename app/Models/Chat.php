<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Chat extends Model
{
    use HasFactory;


    public static function getChatMessages(){
        $result = DB::table('chats')
            ->join('game_users', 'game_users.id', '=', 'chats.userid')
            ->get();

        return $result;

    }

    public static function getChat(){
        return Chat::query()
            ->get();
    }

    public static function resetChatMessages(){
         Chat::query()
            ->truncate();
    }

    public static function sendChatMessage($msg, $correkt){
        Chat::query()
            ->insert([
                'userid' => \Auth::id(),
                'msg' => $msg,
                'correct' => $correkt
            ]);

        //if correct return true

        return false;
    }

}
