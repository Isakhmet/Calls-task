<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('css/semantic.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('css/main.css') }}">
    <script src="{{ secure_asset('js/app.js') }}" defer></script>
</head>
<body>
<div id="app">
    <report-component
        fetch-url="{{ secure_url('api/getCalls') }}"
    ></report-component>
</div>
</body>
</html>
