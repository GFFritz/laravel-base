<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'JusBrasil') }}</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
</head>

<body class="bg-white font-sans leading-normal tracking-normal overflow-x-hidden">
    <main class="mx-auto py-8 flex flex-col items-center">
        {{ $slot }}
    </main>


    <footer class="container mx-auto py-8">

    </footer>

    <script src="{{ mix('js/app.js') }}"></script>

</body>

</html>
