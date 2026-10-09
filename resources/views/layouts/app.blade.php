<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AgriStock')</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%2310b981'%3E%3Cpath d='M23 12l-2.44-2.78.34-3.68-3.61-.82-1.89-3.18L12 3 8.6 1.54 6.71 4.72l-3.61.81.34 3.68L1 12l2.44 2.78-.34 3.69 3.61.82 1.89 3.18L12 21l3.4 1.46 1.89-3.18 3.61-.82-.34-3.68L23 12zm-13 5l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z'/%3E%3C/svg%3E">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="min-vh-100">
    <div class="d-flex min-vh-100">
        
        @if(auth()->check() && auth()->user()->id_rol == 1)
            @include('layouts.sidebaradmin')
        @endif

        @yield('content')
        
    </div>
    {{-- WhatsApp Floating Button --}}
    <a href="https://wa.me/573222673734?text=Hola%20quiero%20hacer%20una%20consulta" target="_blank" style="position: fixed; bottom: 95px; right: 20px; background-color: #25d366; color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; font-size: 35px; box-shadow: 0 4px 15px rgba(37, 211, 102, 0.4); z-index: 9999; transition: transform 0.3s ease, background-color 0.3s ease; text-decoration: none;" onmouseover="this.style.transform='scale(1.1)'; this.style.backgroundColor='#1ebd57'" onmouseout="this.style.transform='scale(1)'; this.style.backgroundColor='#25d366'">
        <i class="fa-brands fa-whatsapp"></i>
    </a>

    @include('layouts.chatbot')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
