<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdn.tailwindcss.com"></script>
        <title>{{ $title ?? 'Guess The Song>' }}</title>
        @livewireStyles
        <script src="/livewire/livewire.js"></script>
        @vite(['resources/js/app.js'])
        @vite(['resources/css/app.css'])
    </head>
    <header>
        <div class="w-full h-20 flex flex-row border-b-2 border-black">
            <div class="w-auto h-20 flex flex-row space-x-3 items-center mx-3">
              <img src="lied.png" class="w-8 h-8">
                <h1 class="text-xl font-bold">
                    Errate den Song!
                </h1>
            </div>
        </div>
    </header>
    <body>
    <div class="w-full h-full items-center">
        {{ $slot }}
    </div>
        @livewireScriptConfig
    </body>
</html>
