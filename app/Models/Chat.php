<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;


    public static function getChatMessages(){
        return Chat::query()
            ->join('chats', 'chats.userid', '=', 'game_users.id')
            ->where('game_users.id', 1)
            ->get();
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
