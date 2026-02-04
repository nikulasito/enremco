@props([
    'title' => 'Admin Control Center - ENREMCO',
])

<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }}</title>

    {{-- App assets (Breeze / Vite) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Fonts & Icons --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">

           <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

           <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />


           
       
    <style>
        body { font-family: "Public Sans", sans-serif; }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-[#f6f8f7] dark:bg-[#112119] min-h-screen text-[#111814]">
    {{ $slot }}
    @stack('scripts')
</body>
</html>
