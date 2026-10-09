@extends('layouts.app')
@section('title', 'Gestión de Vendedores')
@section('content')

<div class="d-flex flex-column gap-4" style="max-width: 1200px; margin: 0 auto; width: 100%;">

    <div class="d-flex justify-content-between align-items-center mb-2">
        <h3 class="fw-bold text-dark mb-0">Equipo de Ventas</h3>
        <button type="button" class="btn btn-primary rounded-3 shadow-sm px-4 fw-medium"
            data-bs-toggle="modal" data-bs-target="#modalCreate"
            style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%); border: none;">
            <i class="fa-solid fa-user-plus me-2"></i>Nuevo Vendedor
        </button>
    </div>

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
                        <th class="px-4 py-3 text-muted" style="font-weight: 600; font-size: 0.8rem;">NOMBRE DEL VENDEDOR</th>
                        <th class="px-4 py-3 text-muted" style="font-weight: 600; font-size: 0.8rem;">CONTACTO</th>
                        <th class="px-4 py-3 text-muted" style="font-weight: 600; font-size: 0.8rem;">ESTADO</th>
                        <th class="px-4 py-3 text-muted text-end" style="font-weight: 600; font-size: 0.8rem;">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($vendedores as $vend)
                        <tr class="border-bottom">
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold"
                                        style="width: 40px; height: 40px;">
                                        {{ strtoupper(substr($vend->usuario, 0, 1)) }}
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">{{ $vend->usuario }}</h6>
                                        <span class="text-muted small">ID: #{{ $vend->id_usuario }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-dark" style="font-size: 0.85rem;">
                                <i class="fa-solid fa-envelope text-muted me-2" style="width: 15px;"></i>{{ $vend->email }}
                            </td>
                            <td class="px-4 py-3">
                                @if ($vend->estado === 'Activo')
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3 py-1">Acceso Permitido</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill px-3 py-1">Acceso Bloqueado</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-end">
                                <button type="button" class="btn btn-sm btn-light border text-primary me-2 shadow-sm rounded-3"
                                    data-bs-toggle="modal" data-bs-target="#modalEdit{{ $vend->id_usuario }}">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <form action="{{ route('vendedores.destroy', $vend->id_usuario) }}" method="POST" class="d-inline form-delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light border text-danger shadow-sm rounded-3">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        {{-- Modal Editar --}}
                        <div class="modal fade" id="modalEdit{{ $vend->id_usuario }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow-lg rounded-4">
                                    <div class="modal-header border-bottom bg-light rounded-top-4">
                                        <h5 class="modal-title fw-bold">Editar Vendedor</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('vendedores.update', $vend->id_usuario) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body p-4">
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold text-muted small">Nombre Completo *</label>
                                                <input type="text" class="form-control bg-light rounded-3" name="nombre"
                                                    value="{{ $vend->usuario }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold text-muted small">Correo Electrónico (Login) *</label>
                                                <input type="email" class="form-control bg-light rounded-3" name="email"
                                                    value="{{ $vend->email }}" required>
                                            </div>
                                            <div class="mb-4">
                                                <label class="form-label fw-semibold text-muted small">Cambiar Contraseña</label>
                                                <input type="text" class="form-control bg-light rounded-3" name="password"
                                                    placeholder="Dejar en blanco para no cambiar">
                                            </div>
                                            <div class="form-check form-switch mt-3 p-3 bg-light rounded-3 border">
                                                <input class="form-check-input ms-0 me-3" type="checkbox" role="switch"
                                                    name="estado" id="estado_vend{{ $vend->id_usuario }}"
                                                    {{ $vend->estado === 'Activo' ? 'checked' : '' }}>
                                                <label class="form-check-label fw-bold text-dark" for="estado_vend{{ $vend->id_usuario }}">
                                                    Cuenta Activa
                                                    <span class="d-block text-muted fw-normal" style="font-size: 0.75rem;">
                                                        Si desactivas esto, el vendedor no podrá iniciar sesión.
                                                    </span>
                                                </label>
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
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-user-tie fs-1 opacity-25 mb-3 d-block"></i>
                                No hay vendedores registrados en el sistema.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Crear --}}
<div class="modal fade" id="modalCreate" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom bg-light rounded-top-4">
                <h5 class="modal-title fw-bold">Registrar Vendedor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('vendedores.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted small">Nombre Completo *</label>
                        <input type="text" class="form-control bg-light rounded-3" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted small">Correo Electrónico (Login) *</label>
                        <input type="email" class="form-control bg-light rounded-3" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted small">Contraseña *</label>
                        <input type="text" class="form-control bg-light rounded-3" name="password" required>
                        <div class="form-text">Con esta contraseña podrá ingresar al sistema de ventas.</div>
                    </div>
                </div>
                <div class="modal-footer border-top bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-light border rounded-3 fw-medium" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-3 fw-medium px-4">Registrar Vendedor</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.form-delete').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'ATENCIÓN: Solo elimina vendedores que nunca hayan hecho una venta. De lo contrario, desactiva su cuenta.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then(function (result) {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
</script>

@endsection
