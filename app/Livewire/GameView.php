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
    public $videoId;
    public $textToGuess;
    public $win = false;

    public function render()
    {
        $messages = Chat::getChatMessages();
        return view('livewire.game-view', ['messages' => $messages]);
    }

    public function mount()
    {
        GameUser::updateUsers(1);

        if (!Song::checkSongExist()) {
            $this->fetchNewSong();
        } else {
            $this->loadCurrentSong();
        }
    }

    public function sendMsg()
    {
        $guess = strtolower($this->message);
        $targetPhrase = strtolower($this->textToGuess);

// Überprüfe, ob die Eingabe des Benutzers mindestens zwei Wörter enthält
        if (str_word_count($guess) < 2) {
            Chat::sendChatMessage("Bitte gib mindestens zwei Wörter ein.", 0);
            return;
        }

        if (stripos($targetPhrase, $guess) !== false) {
            Chat::sendChatMessage($this->message, 1);
            GameUser::setWinning();
        } else {
            Chat::sendChatMessage($this->message, 0);
        }

    }

    public function resetSong()
    {
        Song::resetSong();
        GameUser::resetAllUser();
        Chat::resetChatMessages();
        $this->fetchNewSong();
    }

    // Method to fetch a new song from YouTube playlist
    public function fetchNewSong()
    {
        $playlistId = 'PLs7O5xs_w0WkRuh6w6gQcri57OwAZiHrf';
        $apiKey = 'AIzaSyDiOb5xLvDzMJ32E8sbPHdQHRh1zGv8cY4'; // Replace with your actual API key

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

    // Method to load the current song from the database
    public function loadCurrentSong()
    {
        $currentsong = Song::getSong();
        $this->textToGuess = $currentsong->textToGuess;
        $this->videoId = $currentsong->videoID;
    }

    // Method to sync current song to the database every 1 second
    public function syncCurrentSong()
    {
        Song::updateCurrentSong($this->videoId, $this->textToGuess);
    }

    public function getCurrentSong()
    {
        return Song::getSong();
    }
}
