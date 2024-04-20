<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="icon" href="{{ asset('images/logo/favicon.png') }}" type="image/svg+xml">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="{{ asset('css/output.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    @stack('styles')
</head>
<body class="font-poppins text-[#0A090B]">
    <section id="content" class="flex">
        @include('layouts.partials.sidebar')
        <div id="menu-content" class="flex flex-col w-full pb-[30px]">
            @include('layouts.partials.header')
            @yield('content')
        </div>
    </section>

    @include('sweetalert::alert')

    @stack('scripts')

</body>
</html>
