<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body class="bg-gray-500 antialiased">
    <div>
        {{ $slot }}
    </div>
</body>

</html>
