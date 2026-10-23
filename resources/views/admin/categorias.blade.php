@extends('layouts.app')
@section('title', 'Gestión de Categorías')
@section('content')

<div class="d-flex flex-column gap-4" style="max-width: 1200px; margin: 0 auto; width: 100%;">
    
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h3 class="fw-bold text-dark mb-0">Categorías</h3>
        <button type="button" class="btn btn-primary rounded-3 shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#modalCreate" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%); border: none;">
            <i class="fa-solid fa-plus me-2"></i>Nueva Categoría
        </button>
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
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 text-muted" style="font-weight: 600; font-size: 0.85rem;">ID</th>
                        <th class="px-4 py-3 text-muted" style="font-weight: 600; font-size: 0.85rem;">NOMBRE</th>
                        <th class="px-4 py-3 text-muted" style="font-weight: 600; font-size: 0.85rem;">DESCRIPCIÓN</th>
                        <th class="px-4 py-3 text-muted" style="font-weight: 600; font-size: 0.85rem;">ESTADO</th>
                        <th class="px-4 py-3 text-muted text-end table-actions-cell" style="font-weight: 600; font-size: 0.85rem;">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($categorias && $categorias->count() > 0)
                        @foreach($categorias as $cat)
                            <tr class="border-bottom">
                                <td class="px-4 py-3 fw-bold text-dark">#{{ $cat->id_categoria ?? $cat->id }}</td>
                                <td class="px-4 py-3 fw-semibold text-dark">{{ $cat->nombre }}</td>
                                <td class="px-4 py-3 text-muted text-truncate" style="max-width: 250px;">{{ $cat->descripcion }}</td>
                                <td class="px-4 py-3">
                                    @if ($cat->estado)
                                        <span class="badge-estado-activo">Activo</span>
                                    @else
                                        <span class="badge-estado-inactivo">Inactivo</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center" style="width: 110px;">
                                    <div class="d-flex align-items-center justify-content-center gap-3">
                                        <button type="button" 
                                            class="btn-action-clean text-primary" 
                                            title="Editar" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalEdit{{ $cat->id_categoria ?? $cat->id }}">
                                            <i class="fa-solid fa-pen" style="color: #3b82f6; font-size: 1.05rem;"></i>
                                        </button>
                                        <button type="button" 
                                            class="btn-action-clean text-danger" 
                                            title="Eliminar" 
                                            onclick="confirmarEliminacion('delete-cat-{{ $cat->id_categoria ?? $cat->id }}', '¿Eliminar categoría?', '¿Deseas eliminar la categoría <b>{{ addslashes($cat->nombre) }}</b>?')">
                                            <i class="fa-regular fa-trash-can" style="color: #ef4444; font-size: 1.05rem;"></i>
                                        </button>
                                    </div>
                                    <form id="delete-cat-{{ $cat->id_categoria ?? $cat->id }}" action="{{ route('categorias.destroy', $cat->id_categoria ?? $cat->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>

                            <!-- Modal Editar -->
                            <div class="modal fade" id="modalEdit{{ $cat->id_categoria ?? $cat->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg rounded-4">
                                        <div class="modal-header border-bottom bg-light rounded-top-4">
                                            <h5 class="modal-title fw-bold">Editar Categoría</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('categorias.update', $cat->id_categoria ?? $cat->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body p-4">
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold text-muted small">Nombre de la Categoría</label>
                                                    <input type="text" class="form-control bg-light rounded-3" name="nombre" value="{{ $cat->nombre }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold text-muted small">Descripción</label>
                                                    <textarea class="form-control bg-light rounded-3" name="descripcion" rows="3">{{ $cat->descripcion }}</textarea>
                                                </div>
                                                <div class="form-check form-switch mt-4">
                                                    <input class="form-check-input" type="checkbox" role="switch" name="estado" id="estado{{ $cat->id_categoria ?? $cat->id }}" {{ $cat->estado ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-semibold text-dark" for="estado{{ $cat->id_categoria ?? $cat->id }}">Categoría Activa</label>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-top bg-light rounded-bottom-4">
                                                <button type="button" class="btn btn-light border rounded-3 fw-medium" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-primary rounded-3 fw-medium px-4">Guardar Cambios</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-folder-open fs-1 opacity-25 mb-3 d-block"></i>
                                No hay categorías registradas. Comienza agregando una nueva.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Crear -->
<div class="modal fade" id="modalCreate" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom bg-light rounded-top-4">
                <h5 class="modal-title fw-bold">Nueva Categoría</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('categorias.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted small">Nombre de la Categoría</label>
                        <input type="text" class="form-control bg-light rounded-3" name="nombre" placeholder="Ej. Fertilizantes" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted small">Descripción</label>
                        <textarea class="form-control bg-light rounded-3" name="descripcion" rows="3" placeholder="Descripción breve..."></textarea>
                    </div>
                    <div class="form-check form-switch mt-4">
                        <input class="form-check-input" type="checkbox" role="switch" name="estado" id="estadoNuevo" checked>
                        <label class="form-check-label fw-semibold text-dark" for="estadoNuevo">Categoría Activa</label>
                    </div>
                </div>
                <div class="modal-footer border-top bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-light border rounded-3 fw-medium" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-3 fw-medium px-4">Crear Categoría</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
