<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href=" {{ asset('css/app.css') }} ">
    <link rel="stylesheet" href=" {{ asset('css/all.min.css') }} ">
    <link href="https://fonts.googleapis.com/css?family=Yeon+Sung&display=swap" rel="stylesheet">
    <script src=" {{ asset('js/app.js') }} " defer></script>
    <title>Dashboard</title>
</head>
<body>
    <div id="app">
        <dashboard token="{{ csrf_token() }}" user_json="{{ auth()->user() }}" url="{{url('/')}}" ></dashboard>
    </div>
</body>
</html>