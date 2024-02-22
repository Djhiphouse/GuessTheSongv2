<div>
    <div wire:poll.750ms class="w-full h-full flex flex-row space-x-2">
       <div  id="PlayerBox" class=" mx-3 my-3 h-full w-auto flex flex-col items-center border border-black space-y-1 rounded-xl">
           <div class="w-full h-auto flex justify-center items-center border-b border-black">
               <h1 class="mx-2">
                   Spieler Liste
               </h1>
           </div>
           @foreach(\App\Models\GameUser::getUsers() as $user)
               <div class="w-auto h-auto flex flex-row space-x-2 items-center justify-center">
                   <h1 class="font-bold mx-2">
                       {{$user->username}}
                   </h1>

                   <div class="w-full h-auto flex flex-row space-x-2 items-center justify-center">
                       <span class="bg-green-100 text-green-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded">Online</span>

                       @if($user->win == "1")
                           <img src="trophy.png" class="w-8 h-8">
                       @else
                           <img src="lose.png" class="w-8 h-8">
                       @endif
                   </div>

               </div>

           @endforeach
       </div>
        <div id="song_selection" class="my-3 rounded-xl w-[50%] h-[75%] flex flex-col justify-center items-center space-y-2 border border-black relative">
            <div class="mx-3 flex flex-row space-x-2 items-center">
                <img src="play.png" class="w-10 h-10">
                <h1 class="font-bold">Welcher Song ist das?</h1>
            </div>
            <script>
                var webSocket = new WebSocket("ws://localhost:8080/ws");
                webSocket.onmessage = function(event) {
                    console.log(event.data);
                   console.log("WS: " + event.data);
                   if (event.data === "Reset_song") {
                       location.reload();
                   }
                };
                function sendMessage() {
                    webSocket.send("Reset_song");
                }
            </script>
            <div class="relative flex flex-col items-center">
                <iframe wire:ignore id="yt_song" class="sm:w-auto sm:h-auto md:w-[400px] md:h-[260px]" src="https://www.youtube.com/embed/{{ $videoId }}?autoplay=1&mute=0&controls=0&showinfo=0&modestbranding=1&loop=1" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                @if(\App\Models\GameUser::chechWin() == true)
                    <h1 class=" font-bold">
                        Song war: {{$textToGuess}}
                    </h1>
                @endif
                <!-- Overlay div positioned over the iframe window -->
                <div class="absolute inset-0 h-[20%] bg-white"></div>
            </div>
            <h1 class="font-bold text-2xl">90s Edition</h1>
        </div>



        <div id="chat_box" class="mx-3 rounded-xl w-auto h-auto flex flex-col items-center space-y-2 border border-black my-3">
            <div class="w-full h-auto border-b border-black justify-center items-center flex flex-row">
                <h1 class="mx-3 font-bold">ChatBox</h1>
            </div>

            <div class="overflow-y-auto h-64 w-full flex flex-col-reverse">
                @foreach(\App\Models\Chat::getChatMessages() as $message)
                    <div class="w-full flex flex-row mx-2 my-1">
                        @if($message->correct == 1)
                            <div class="w-8 h-8 rounded-full bg-green-700 flex flex-row justify-center items-center mx-2">
                                <h1>{{ substr($message->username, 0, 1) }}</h1>
                            </div>
                            <h1 class="text-green-800 font-bold">Erraten!</h1>
                        @else
                            <div class="w-8 h-8 rounded-full bg-pink-300 flex flex-row justify-center items-center mx-2">
                                <h1>{{ substr($message->username, 0, 1) }}</h1>
                            </div>
                            <h1 class="font-bold ml-2">{{ $message->msg }}</h1>
                        @endif
                    </div>
                @endforeach
            </div>

            <script>
                function resetTextBox() {
                    document.getElementById('message_box').value = "";
                    sendMessage();
                }
            </script>

            <div class="flex w-full h-auto flex-row space-x-2 items-center">
                @if(\App\Models\GameUser::chechWin() == false)
                    <input id="message_box" wire:model="message" type="text" placeholder="Song Namen..." class="input input-bordered w-full max-w-xs ml-2" />
                    <button wire:click="sendMsg" class=" bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Senden
                    </button>
                @else
                    <input disabled id="message_box" wire:model="message" type="text" placeholder="Richtig!" class="input input-bordered w-full max-w-xs opacity-20" />
                    <button disabled onclick="resetTextBox()" wire:click="sendMsg" class="mr-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded opacity-20">
                        Senden
                    </button>
                @endif
            </div>
        </div>
        <button onclick="sendMessage()" wire:click="resetSong" class="mr-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3">
            Reset
        </button>
    </div>
</div>
