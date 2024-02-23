<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Song extends Model
{
    use HasFactory;
    public $timestamps = false;

    public static function getSong(){
        return Song::query()
            ->where('used', 0)
            ->latest()
            ->first();
    }




    public static function resetSong(){
        return Song::query()
            ->where('used', 0)
            ->update([
                'used' => 1
            ]);
    }

    public static function checkSongExist(){
         if (Song::query()->where('used', 0)->count() > 0)
             return true;
         else
             return  false;
    }


    public  static function insertSongToDatabase($videoID, $texttoguess){
        Song::query()
            ->insert([
                'videoID' => $videoID,
                'textToGuess' => $texttoguess,
                'used' => 0
            ]);
    }
}
