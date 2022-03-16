<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Crawler - @yield('title')</title>

        <link rel="stylesheet" type="text/css" href="/css/crawler.css">
        <script type="text/javascript" src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
        <script type="text/javascript" src="/js/crawler.js"></script>  

    </head>
    <body>
     @yield('body')
    </body>
</html>
