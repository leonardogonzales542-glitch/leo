@extends('layouts.app')
@section('title', 'Gestión de Inventario')
@section('content')
<div class="d-flex flex-column gap-4" style="max-width: 1200px; margin: 0 auto; width: 100%;">
    
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h3 class="fw-bold text-dark mb-1">Inventario</h3>
            <span class="text-muted small">{{ $tituloPagina }}</span>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('productos.index') }}{{ $filtro == 'stock_bajo' ? '' : '?filtro=stock_bajo' }}" class="btn {{ $filtro == 'stock_bajo' ? 'btn-danger' : 'btn-outline-danger bg-white' }} rounded-3 shadow-sm px-3 fw-medium">
                <i class="fa-solid fa-triangle-exclamation me-2"></i>{{ $filtro == 'stock_bajo' ? 'Ver Todos' : 'Ver Stock Bajo' }}
            </a>
            <a href="{{ route('productos.create') }}" class="btn btn-primary rounded-3 shadow-sm px-4 fw-medium" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%); border: none;">
                <i class="fa-solid fa-plus me-2"></i>Nuevo Producto
            </a>
        </div>
    </div>

    <!-- Mensajes -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert">
            <i class="fa-solid fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            @if ($productos && $productos->count() > 0)
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 text-muted" style="font-weight: 600; font-size: 0.8rem;">CÓDIGO</th>
                        <th class="px-4 py-3 text-muted" style="font-weight: 600; font-size: 0.8rem;">PRODUCTO</th>
                        <th class="px-4 py-3 text-muted" style="font-weight: 600; font-size: 0.8rem;">CATEGORÍA/PROV.</th>
                        <th class="px-4 py-3 text-muted" style="font-weight: 600; font-size: 0.8rem;">PRECIO V.</th>
                        <th class="px-4 py-3 text-muted" style="font-weight: 600; font-size: 0.8rem;">STOCK</th>
                        <th class="px-4 py-3 text-muted" style="font-weight: 600; font-size: 0.8rem;">ESTADO</th>
                        <th class="px-4 py-3 text-muted text-end table-actions-cell" style="font-weight: 600; font-size: 0.8rem;">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productos as $p)
                        <?php 
                            $stockStatusClass = 'success';
                            if ($p->stock_actual <= 0) $stockStatusClass = 'danger';
                            elseif ($p->stock_actual <= $p->stock_minimo) $stockStatusClass = 'warning';
                        ?>
                        <tr class="border-bottom">
                            <td class="px-4 py-3 fw-bold text-dark" style="font-size: 0.85rem; white-space: nowrap;">{{ $p->codigo }}</td>
                            <td class="px-4 py-3 fw-semibold text-dark" style="min-width: 180px;">
                                <div class="d-flex align-items-center gap-3">
                                    @if (!empty($p->imagen) && $p->imagen != 'default.png')
                                        <img src="{{ asset('img/productos/' . $p->imagen) }}" alt="Img" width="40" height="40" class="rounded-3 object-fit-cover shadow-sm border flex-shrink-0">
                                    @else
                                        <div class="bg-light rounded-3 border d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
                                            <i class="fa-solid fa-image text-muted opacity-50"></i>
                                        </div>
                                    @endif
                                    <span>{{ $p->nombre }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-muted small">
                                {{ $p->categoria->nombre ?? 'Sin categoría' }}<br>
                                <span class="text-secondary" style="font-size: 0.75rem;"><i class="fa-solid fa-truck me-1"></i>{{ $p->proveedor->razon_social ?? 'Sin proveedor' }}</span>
                            </td>
                            <td class="px-4 py-3 fw-bold text-success">${{ number_format($p->precio_venta, 2, ',', '.') }}</td>
                            <td class="px-4 py-3">
                                <span class="badge bg-{{ $stockStatusClass }} bg-opacity-10 text-{{ $stockStatusClass }} border border-{{ $stockStatusClass }} border-opacity-25 rounded-pill px-2 py-1">
                                    {{ floatval($p->stock_actual) }} {{ $p->unidad_medida }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @if ($p->estado)
                                    <i class="fa-solid fa-circle-check text-success"></i>
                                @else
                                    <i class="fa-solid fa-circle-xmark text-danger"></i>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center" style="width: 110px;">
                                <div class="d-flex align-items-center justify-content-center gap-3">
                                    <button type="button" 
                                        class="btn-action-clean text-primary" 
                                        title="Editar" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalEdit{{ $p->id_producto }}">
                                        <i class="fa-solid fa-pen" style="color: #3b82f6; font-size: 1.05rem;"></i>
                                    </button>
                                    <button type="button" 
                                        class="btn-action-clean text-danger" 
                                        title="Eliminar" 
                                        onclick="confirmarEliminacion('delete-form-{{ $p->id_producto }}', '¿Eliminar producto?', '¿Deseas eliminar permanentemente <b>{{ addslashes($p->nombre) }}</b>?')">
                                        <i class="fa-regular fa-trash-can" style="color: #ef4444; font-size: 1.05rem;"></i>
                                    </button>
                                </div>
                                <form id="delete-form-{{ $p->id_producto }}" action="{{ route('productos.destroy', $p->id_producto) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>

                        <!-- Modal Editar -->
                        <div class="modal fade" id="modalEdit{{ $p->id_producto }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content border-0 shadow-lg rounded-4">
                                    <div class="modal-header border-bottom bg-light rounded-top-4">
                                        <h5 class="modal-title fw-bold">Editar Producto</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('productos.update', $p->id_producto) }}" method="POST" enctype="multipart/form-data">
                                        <div class="modal-body p-4">
                                            @csrf @method('PUT')
                                            
                                            
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label class="form-label fw-semibold text-muted small">Código</label>
                                                    <input type="text" class="form-control bg-light rounded-3" name="codigo" value="{{ $p->codigo }}" required>
                                                </div>
                                                <div class="col-md-8">
                                                    <label class="form-label fw-semibold text-muted small">Nombre del Producto</label>
                                                    <input type="text" class="form-control bg-light rounded-3" name="nombre" value="{{ $p->nombre }}" required>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <label class="form-label fw-semibold text-muted small">Categoría</label>
                                                    <select class="form-select bg-light rounded-3" name="id_categoria" required>
                                                        @foreach($categorias as $cat)
                                                            <option value="{{ $cat->id_categoria }}" {{ $cat->id_categoria == $p->id_categoria ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label fw-semibold text-muted small">Proveedor</label>
                                                    <select class="form-select bg-light rounded-3" name="id_proveedor">
                                                        <option value="">Sin proveedor</option>
                                                        @foreach($proveedores as $prov)
                                                            <option value="{{ $prov->id_proveedor }}" {{ (isset($p->id_proveedor) && $p->id_proveedor == $prov->id_proveedor) ? 'selected' : '' }}>{{ $prov->razon_social }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label fw-semibold text-muted small">Unidad (ej. Kg, Und)</label>
                                                    <input type="text" class="form-control bg-light rounded-3" name="unidad_medida" value="{{ $p->unidad_medida }}" required>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold text-muted small">Precio de Compra</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-light border-end-0">$</span>
                                                        <input type="number" step="0.01" class="form-control bg-light border-start-0 ps-0 rounded-end-3" name="precio_compra" value="{{ $p->precio_compra }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold text-muted small">Precio de Venta</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-light border-end-0 text-success fw-bold">$</span>
                                                        <input type="number" step="0.01" class="form-control bg-light border-start-0 ps-0 rounded-end-3" name="precio_venta" value="{{ $p->precio_venta }}" required>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold text-muted small">Stock Actual</label>
                                                    <input type="number" step="0.01" class="form-control bg-light rounded-3" name="stock_actual" value="{{ $p->stock_actual }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold text-muted small">Stock Mínimo (Alerta)</label>
                                                    <input type="number" step="0.01" class="form-control bg-light rounded-3" name="stock_minimo" value="{{ $p->stock_minimo }}" required>
                                                </div>

                                                <div class="col-12">
                                                    <label class="form-label fw-semibold text-muted small">Descripción</label>
                                                    <textarea class="form-control bg-light rounded-3" name="descripcion" rows="2">{{ $p->descripcion }}</textarea>
                                                </div>
                                                
                                                <div class="col-12 mt-3">
                                                    <label class="form-label fw-semibold text-muted small">Imagen del Producto (Opcional)</label>
                                                    <input type="file" class="form-control bg-light rounded-3" name="imagen" accept="image/*">
                                                    @if (!empty($p->imagen) && $p->imagen != 'default.png')
                                                        <div class="mt-2 text-muted small">
                                                            <i class="fa-solid fa-check text-success me-1"></i> Imagen actual: {{ $p->imagen }}
                                                        </div>
                                                    @endif
                                                </div>
                                                
                                                <div class="col-12 mt-3">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" role="switch" name="estado" id="estado{{ $p->id_producto }}" {{ $p->estado ? 'checked' : '' }}>
                                                        <label class="form-check-label fw-semibold text-dark" for="estado{{ $p->id_producto }}">Producto Activo para la Venta</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-top bg-light rounded-bottom-4">
                                            <button type="button" class="btn btn-light border rounded-3 fw-medium" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-primary rounded-3 fw-medium px-4">Actualizar Producto</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    @endforeach
                </tbody>
            </table>
            @else
            <!-- Estado vacío pulido y centrado -->
            <div class="d-flex flex-column align-items-center justify-content-center py-5 text-center">
                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                    <i class="fa-solid fa-box-open fs-1 text-muted opacity-50"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2">No hay productos en el inventario</h5>
                <p class="text-muted mb-4" style="max-width: 400px;">Aún no has registrado ningún artículo para la venta. Empieza agregando tu primer producto al sistema.</p>
                <a href="{{ route('productos.create') }}" class="btn btn-success rounded-3 shadow-sm px-4 fw-medium">
                    <i class="fa-solid fa-plus me-2"></i>Agrega tu primer producto
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
