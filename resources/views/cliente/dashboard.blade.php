@extends('layouts.app')
@section('title', 'Mi Panel | AgriStock')
@section('content')

<style>
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15) !important;
    }
</style>

<div class="d-flex flex-column gap-4" style="max-width: 1300px; margin: 0 auto; width: 100%;">

    {{-- Bienvenida --}}
    <div>
        <h3 class="fw-bold text-dark mb-0">Bienvenido, {{ $nombreUsuario }} 👋</h3>
        <span class="text-muted small">Panel de Cliente — AgriStock</span>
    </div>

    {{-- Tarjetas de resumen --}}
    <div class="row g-4">
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 hover-lift" style="transition: transform 0.2s;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h6 class="text-muted fw-semibold mb-1">Mis Pedidos</h6>
                            <h2 class="fw-bold text-dark mb-0">0</h2>
                        </div>
                        <div class="bg-success bg-opacity-10 text-success rounded-4 p-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                            <i class="fa-solid fa-shopping-bag fa-lg"></i>
                        </div>
                    </div>
                    <p class="text-muted small mb-0"><i class="fa-solid fa-arrow-right text-success me-1"></i> Ir a la tienda</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 hover-lift" style="transition: transform 0.2s;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h6 class="text-muted fw-semibold mb-1">Gastos del Mes</h6>
                            <h2 class="fw-bold text-dark mb-0">$0.00</h2>
                        </div>
                        <div class="bg-primary bg-opacity-10 text-primary rounded-4 p-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                            <i class="fa-solid fa-chart-line fa-lg"></i>
                        </div>
                    </div>
                    <p class="text-muted small mb-0">Mantén el control de tus compras</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 hover-lift" style="transition: transform 0.2s;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h6 class="text-muted fw-semibold mb-1">Puntos AgriStock</h6>
                            <h2 class="fw-bold text-dark mb-0">0 pts</h2>
                        </div>
                        <div class="bg-warning bg-opacity-10 text-warning rounded-4 p-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                            <i class="fa-solid fa-star fa-lg"></i>
                        </div>
                    </div>
                    <p class="text-muted small mb-0">Acumula puntos con cada compra</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Catálogo --}}
    <div>
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <h4 class="fw-bold text-dark mb-0">
                <i class="fa-solid fa-store text-success me-2"></i>Catálogo de Productos
            </h4>
            <div class="position-relative" style="max-width: 350px; width: 100%;">
                <span class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted">
                    <i class="fa-solid fa-search"></i>
                </span>
                <input type="text" id="searchInput" class="form-control rounded-pill ps-5 py-2 shadow-sm border-0 bg-white" placeholder="Buscar productos (ej. Purina)..." onkeyup="filterProducts()">
            </div>
        </div>

        @if($productos->isNotEmpty())
            <div class="row g-4" id="productsGrid">
                @foreach($productos as $p)
                    <div class="col-12 col-sm-6 col-lg-4 col-xl-3 product-card-container">
                        <div class="card border-0 shadow-sm rounded-4 h-100 hover-lift overflow-hidden" style="transition: transform 0.2s;">
                            @php
                                $imageFile = 'img/productos/' . $p->imagen;
                                $hasImage = !empty($p->imagen) && $p->imagen !== 'default.png' && file_exists(public_path($imageFile));
                            @endphp
                            <div class="position-relative bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                @if($hasImage)
                                    <img src="{{ asset($imageFile) }}" class="w-100 h-100 object-fit-contain p-3" alt="{{ $p->nombre }}">
                                @else
                                    <i class="fa-solid fa-box-open text-success opacity-25" style="font-size: 5rem;"></i>
                                @endif
                                <div class="position-absolute top-0 end-0 m-2">
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-2 py-1">
                                        ${{ number_format($p->precio_venta, 2) }}
                                    </span>
                                </div>
                            </div>
                            <div class="card-body p-4 d-flex flex-column">
                                <small class="text-muted fw-semibold mb-1">{{ $p->categoria_nombre ?? 'Sin categoría' }}</small>
                                <h6 class="fw-bold text-dark mb-2">{{ $p->nombre }}</h6>
                                <p class="text-secondary small mb-3 flex-grow-1"
                                    style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    {{ $p->descripcion }}
                                </p>
                                <button class="btn btn-outline-success w-100 rounded-pill fw-medium btn-sm mt-auto p-2">
                                    <i class="fa-solid fa-cart-plus me-2"></i>Añadir al Carrito
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-5 text-center">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 80px; height: 80px;">
                        <i class="fa-solid fa-box-open fa-2x text-muted"></i>
                    </div>
                    <h6 class="fw-semibold text-dark">No hay productos disponibles por el momento.</h6>
                </div>
            </div>
        @endif
    </div>

    {{-- Banner de contacto --}}
    <div class="card border-0 shadow-sm rounded-4 text-white mb-2" style="background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);">
        <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 text-center text-md-start">
            <div class="d-flex align-items-center gap-4">
                <i class="fa-solid fa-headset fa-3x opacity-75"></i>
                <div>
                    <h4 class="fw-bold mb-1">¿Necesitas asesoría con tus compras?</h4>
                    <p class="mb-0 opacity-75">Nuestro equipo está disponible para ayudarte a elegir los mejores productos.</p>
                </div>
            </div>
            <a href="https://wa.me/573222673734" target="_blank"
                class="btn btn-light text-success fw-bold rounded-pill px-4 py-2 shadow-sm text-nowrap d-flex align-items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/>
                </svg>
                Contactar por WhatsApp
            </a>
        </div>
    </div>

</div>

@push('scripts')
<script>
    function filterProducts() {
        const input = document.getElementById('searchInput');
        const filter = input.value.toLowerCase();
        const grid = document.getElementById('productsGrid');
        if(!grid) return;
        const cards = grid.getElementsByClassName('product-card-container');
        
        for (let i = 0; i < cards.length; i++) {
            const card = cards[i];
            const title = card.querySelector('h6.fw-bold').innerText.toLowerCase();
            const category = card.querySelector('small.text-muted').innerText.toLowerCase();
            
            if (title.includes(filter) || category.includes(filter)) {
                card.style.display = "";
            } else {
                card.style.display = "none";
            }
        }
    }
</script>
@endpush
@endsection
