<!DOCTYPE html>
<html lang="en">
<head>
    <title>CoachSparkle</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="icon" type="image/png" href="{{ asset('imges/favicon.png') }}" />

    <!-- Stylesheets -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="{{ asset('style.css') }}" rel="stylesheet" />
    <link href="{{ asset('signup.css') }}" rel="stylesheet" />

    <!-- Vite (or Mix) -->
    @viteReactRefresh
    @vite('resources/js/app.jsx')
</head>
<body>
    <div id="react-root"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
