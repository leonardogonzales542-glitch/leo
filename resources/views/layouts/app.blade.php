<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0b1020">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="AgriStock">
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/icons/icon-192.png">
    <title>@yield('title', 'AgriStock')</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%2310b981'%3E%3Cpath d='M23 12l-2.44-2.78.34-3.68-3.61-.82-1.89-3.18L12 3 8.6 1.54 6.71 4.72l-3.61.81.34 3.68L1 12l2.44 2.78-.34 3.69 3.61.82 1.89 3.18L12 21l3.4 1.46 1.89-3.18 3.61-.82-.34-3.68L23 12zm-13 5l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z'/%3E%3C/svg%3E">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        /* Mobile layout adaptations (Portrait / Vertical) */
        @media (max-width: 991.98px) and (orientation: portrait) {
            .app-content-body {
                padding-bottom: 95px !important;
            }
            .wa-float-btn {
                bottom: 150px !important;
                right: 16px !important;
                width: 48px !important;
                height: 48px !important;
                font-size: 26px !important;
            }
            .chatbot-toggle-btn {
                bottom: 88px !important;
                right: 16px !important;
                width: 48px !important;
                height: 48px !important;
            }
            .chatbot-window {
                bottom: 145px !important;
                right: 14px !important;
            }
        }

        /* Mobile layout adaptations (Landscape / Horizontal - Pantalla ancha y baja) */
        @media (max-width: 991.98px) and (orientation: landscape), (max-height: 520px) {
            .app-content-body {
                padding: 10px 16px 75px 16px !important;
            }
            header.position-sticky {
                padding-top: 4px !important;
                padding-bottom: 4px !important;
            }
            /* Barra inferior compacta para no tapar los datos */
            .mobile-bottom-dock {
                height: 44px !important;
                bottom: 6px !important;
                max-width: 420px !important;
                padding: 2px 8px !important;
                border-radius: 22px !important;
                background: rgba(15, 23, 42, 0.95) !important;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5) !important;
            }
            .dock-item {
                padding: 2px 8px !important;
                font-size: 0.6rem !important;
                gap: 1px !important;
            }
            .dock-item i {
                font-size: 0.95rem !important;
            }
            /* Botones flotantes (WhatsApp y Chat) ordenados en la esquina derecha sin estorbar el centro */
            .wa-float-btn {
                bottom: 56px !important;
                right: 12px !important;
                width: 38px !important;
                height: 38px !important;
                font-size: 20px !important;
                z-index: 1040 !important;
            }
            .chatbot-container {
                bottom: 10px !important;
                right: 12px !important;
                z-index: 1040 !important;
            }
            .chatbot-toggle-btn {
                bottom: 10px !important;
                right: 12px !important;
                width: 38px !important;
                height: 38px !important;
                font-size: 1.15rem !important;
            }
            .chatbot-window {
                bottom: 52px !important;
                right: 8px !important;
                width: 310px !important;
                height: 270px !important;
            }
            /* Tarjetas más compactas */
            .fintech-card {
                padding: 10px 14px !important;
                border-radius: 14px !important;
            }
            .month-selector-bar {
                margin-bottom: 6px !important;
                padding: 3px 8px !important;
            }
            .mini-bar-pair {
                height: 48px !important;
            }
            .chart-box-wrapper {
                height: 160px !important;
            }
        }
    </style>
</head>
<body class="min-vh-100 bg-body">
    <div class="d-flex min-vh-100">
        
        @if(auth()->check())
            @include('layouts.sidebaradmin')
        @endif

        @yield('content')
        
        @if(auth()->check())
        </div>{{-- cierra .p-4.p-lg-5.app-content-body abierto en sidebaradmin --}}
        @include('layouts.footer')
        </main>{{-- cierra <main> abierto en sidebaradmin --}}
        @else
        @include('layouts.footer')
        @endif
        
    </div>

    @if(auth()->check())
        @include('layouts.mobile_bottom_nav')
    @endif

    {{-- WhatsApp Floating Button --}}
    <a href="https://wa.me/573222673734?text=Hola%20quiero%20hacer%20una%20consulta" target="_blank" class="wa-float-btn" style="position: fixed; bottom: 95px; right: 20px; background-color: #25d366; color: white; border-radius: 50%; width: 56px; height: 56px; display: flex; align-items: center; justify-content: center; font-size: 32px; box-shadow: 0 4px 15px rgba(37, 211, 102, 0.4); z-index: 999; transition: transform 0.3s ease, background-color 0.3s ease; text-decoration: none;" onmouseover="this.style.transform='scale(1.1)'; this.style.backgroundColor='#1ebd57'" onmouseout="this.style.transform='scale(1)'; this.style.backgroundColor='#25d366'">
        <i class="fa-brands fa-whatsapp"></i>
    </a>

    @include('layouts.chatbot')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmarEliminacion(formId, titulo, texto) {
            Swal.fire({
                title: titulo || '¿Estás seguro?',
                html: `<span class="text-muted">${texto || 'Esta acción no se puede deshacer.'}</span>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="fa-solid fa-trash-can me-1"></i> Sí, eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-4 shadow-lg border-0',
                    confirmButton: 'btn btn-danger rounded-pill px-4 py-2 fw-semibold shadow-sm',
                    cancelButton: 'btn btn-light border rounded-pill px-4 py-2 fw-semibold me-2'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }
    </script>
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').catch(err => {
                    console.log('SW registration failed:', err);
                });
            });
        }
        if (screen.orientation && screen.orientation.unlock) {
            try { screen.orientation.unlock(); } catch (e) {}
        }
    </script>
    @stack('scripts')
</body>
</html>
