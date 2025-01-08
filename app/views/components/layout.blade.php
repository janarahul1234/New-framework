<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!--==================== CSS ====================-->
    <link rel="stylesheet" href="@asset('assets/css/styles.css')">

    <!--==================== TITLE ====================-->
    <title>{{ $title ?? 'Coffee Shop' }}</title>
</head>

<body>
    <!--==================== HEADER ====================-->
    <x-header />

    <!--==================== MAIN ====================-->
    <main class="main">
        {!! $slot !!}
    </main>

    <!--==================== FOOTER ====================-->
    <x-footer />
</body>

</html>
