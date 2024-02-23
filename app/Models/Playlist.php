<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    use HasFactory;


    public static function getCurrentPlaylist(){
        return Playlist::query()
            ->where('used', 0)
            ->latest()
            ->first()->playlistID;
    }

    public static function insertPlaylist($playlistid){
        return Playlist::query()
            ->insert([
                'playlistID' => $playlistid,
                'used' => 0
            ]);
    }
}
