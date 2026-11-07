<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sugihart Device Manager</title>
    <link rel="stylesheet" href="{{ \Sugihart\DeviceManager\Support\Urls::asset('elmer/dist/css/style.css') }}">
</head>
<body>
    <main style="max-width:720px;margin:4rem auto;padding:1rem">
        <p><a href="{{ route('sdm.login') }}">Login Device Manager</a></p>
        @yield('content')
    </main>
</body>
</html>
