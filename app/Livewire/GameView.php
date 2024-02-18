<?php

namespace App\Livewire;

use App\Models\Chat;
use App\Models\GameUser;
use App\Models\Song;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class GameView extends Component
{


    public $message;
    public function render()
    {
        $messages = Chat::getChatMessages();
        return view('livewire.game-view', ['messages' => $messages]);
    }
    public $videoId;
    public $textToGuess;
    public $win = false;

    public function mount()
    {
        GameUser::updateUsers(1);
        if (!Song::checkSongExist()){
            $playlistId = 'PLs7O5xs_w0WkRuh6w6gQcri57OwAZiHrf';
            $apiKey = 'AIzaSyDiOb5xLvDzMJ32E8sbPHdQHRh1zGv8cY4';

            $response = Http::get('https://www.googleapis.com/youtube/v3/playlistItems', [
                'part' => 'snippet',
                'playlistId' => $playlistId,
                'maxResults' => 50, // Adjust this according to your playlist size
                'key' => $apiKey,
            ]);

            $videos = $response->json()['items'];
            $randomVideo = $videos[array_rand($videos)];
            $this->videoId = $randomVideo['snippet']['resourceId']['videoId'];
            $this->textToGuess = $randomVideo['snippet']['title'];
            Song::insertSongToDatabase($this->videoId, $this->textToGuess);
        }



            $currentsong = Song::getSong();
            $this->textToGuess = $currentsong->textToGuess;
            $this->videoId = $currentsong->videoID;



    }


    public function sendMsg(){
        $position = stripos(strtolower($this->textToGuess), strtolower($this->message));
        if ($position !== false) {
            Chat::sendChatMessage($this->message, 1);
            GameUser::setWinning();
        } else {
            Chat::sendChatMessage($this->message, 0);
        }
    }

    public function resetSong(){
        Song::resetSong();
        GameUser::resetAllUser();
        Chat::resetChatMessages();
        $playlistId = 'PLs7O5xs_w0WkRuh6w6gQcri57OwAZiHrf';
        $apiKey = 'AIzaSyDiOb5xLvDzMJ32E8sbPHdQHRh1zGv8cY4';

        $response = Http::get('https://www.googleapis.com/youtube/v3/playlistItems', [
            'part' => 'snippet',
            'playlistId' => $playlistId,
            'maxResults' => 50, // Adjust this according to your playlist size
            'key' => $apiKey,
        ]);

        $videos = $response->json()['items'];
        $randomVideo = $videos[array_rand($videos)];
        $this->videoId = $randomVideo['snippet']['resourceId']['videoId'];
        $this->textToGuess = $randomVideo['snippet']['title'];
        Song::insertSongToDatabase($this->videoId, $this->textToGuess);


    }





}
