<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameUser extends Model
{
    use HasFactory;



    public  static function getUsers(){
        return GameUser::query()
            ->where('state', 1)
            ->get();
    }
    public  static function setWinning(){
        return GameUser::query()
            ->where('id', \Auth::id())
            ->update([
                'win' => 1
            ]);
    }

    public  static function resetAllUser(){
        return GameUser::query()
            ->update([
                'win' => 0
            ]);
    }

    public  static function chechWin(){
        $win = GameUser::query()
            ->where('id', \Auth::id())
            ->where('win', 1)
            ->count();


        if ($win > 0)
            return true;
        else
            return false;
    }

    public static function updateUsers($state)
    {
        $existingUser = GameUser::query()->where('username', \Auth::user()->name)->first();

        if (!$existingUser) {
            // If the user doesn't exist, insert a new record
            return GameUser::query()->insert([
                'username' => \Auth::user()->name,
                'state' => 1,
                'totalwins' => 0,
                'win' => 0
            ]);
        } else {
            // If the user exists, update their state
            return GameUser::query()->where('id', $existingUser->id)->update([
                'state' => $state
            ]);
        }
    }

}
