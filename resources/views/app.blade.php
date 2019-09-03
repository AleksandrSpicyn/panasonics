<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }} ">
    <meta property="og:title" content="{{__('app.power_up')}} - {{__('app.show_talants')}}!"/>
    <meta property="og:description" content="{{__('app.show_text')}}"/>
    <meta property="og:url" content="{{ ENV('APP_URL') }}"/>
    <meta property="og:image" content="https://cds.ru/img/social/crystal.png">
    <link rel="shortcut icon" href="/favicon1.ico">
    <title>Panasonic</title>
    <link href="/cirquedusoleil/public/css/app.css" rel="stylesheet">
    <script src="/cirquedusoleil/public/css/app.css"></script>
        mindbox = window.mindbox || function () {
            mindbox.queue.push(arguments);
        };
        mindbox.queue = mindbox.queue || [];
        mindbox('create');
    </script>
    <script src="https://api.mindbox.ru/scripts/v1/tracker.js" async></script>
</head>
<body>
@yield('content')
</body>
</html>