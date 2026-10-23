@extends('layouts.app')
@section('title', 'Cupones')
@section('content')

<div class="d-flex flex-column gap-4" style="max-width: 1200px; margin: 0 auto; width: 100%;">

    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h3 class="fw-bold text-dark mb-0">Cupones de Descuento</h3>
            <span class="text-muted small">Gestiona los cupones de descuento</span>
        </div>
        <button type="button" class="btn btn-primary rounded-3 shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#modalCreate" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%); border: none;">
            <i class="fa-solid fa-plus me-2"></i>Nuevo Cupón
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
                        <th class="px-4 py-3 text-muted" style="font-weight: 600; font-size: 0.85rem;">CÓDIGO</th>
                        <th class="px-4 py-3 text-muted" style="font-weight: 600; font-size: 0.85rem;">DESCUENTO</th>
                        <th class="px-4 py-3 text-muted" style="font-weight: 600; font-size: 0.85rem;">VIGENCIA</th>
                        <th class="px-4 py-3 text-muted" style="font-weight: 600; font-size: 0.85rem;">ESTADO</th>
                        <th class="px-4 py-3 text-muted text-end" style="font-weight: 600; font-size: 0.85rem;">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cupones as $cupon)
                        <tr class="border-bottom">
                            <td class="px-4 py-3 fw-bold text-dark">{{ $cupon->codigo }}</td>
                            <td class="px-4 py-3 fw-semibold text-dark">
                                {{ $cupon->descuento }}{{ $cupon->tipo === 'Porcentaje' ? '%' : ' $' }}
                                <br><small class="text-muted fw-normal">{{ $cupon->tipo }}</small>
                            </td>
                            <td class="px-4 py-3 text-muted">
                                <small class="d-block"><strong>Inicio:</strong> {{ $cupon->fecha_inicio ? $cupon->fecha_inicio->format('d/m/Y') : 'N/A' }}</small>
                                <small class="d-block"><strong>Fin:</strong> {{ $cupon->fecha_fin ? $cupon->fecha_fin->format('d/m/Y') : 'N/A' }}</small>
                            </td>
                            <td class="px-4 py-3">
                                @if ($cupon->estado)
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
                                        data-bs-target="#modalEdit{{ $cupon->id_cupon }}">
                                        <i class="fa-solid fa-pen" style="color: #3b82f6; font-size: 1.05rem;"></i>
                                    </button>
                                    <button type="button" 
                                        class="btn-action-clean text-danger" 
                                        title="Eliminar" 
                                        onclick="confirmarEliminacion('delete-cupon-{{ $cupon->id_cupon }}', '¿Eliminar cupón?', '¿Deseas eliminar el cupón <b>{{ addslashes($cupon->codigo) }}</b>?')">
                                        <i class="fa-regular fa-trash-can" style="color: #ef4444; font-size: 1.05rem;"></i>
                                    </button>
                                </div>
                                <form id="delete-cupon-{{ $cupon->id_cupon }}" action="{{ route('cupones.destroy', $cupon->id_cupon) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>

                        {{-- Modal Editar --}}
                        <div class="modal fade" id="modalEdit{{ $cupon->id_cupon }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow-lg rounded-4">
                                    <div class="modal-header border-bottom bg-light rounded-top-4">
                                        <h5 class="modal-title fw-bold">Editar Cupón</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('cupones.update', $cupon->id_cupon) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body p-4">
                                            @include('admin.cupon_form', ['cupon' => $cupon])
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
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-ticket fs-1 opacity-25 mb-3 d-block"></i>
                                No hay cupones registrados. Comienza agregando uno nuevo.
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
                <h5 class="modal-title fw-bold">Nuevo Cupón</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('cupones.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    @include('admin.cupon_form', ['cupon' => null])
                </div>
                <div class="modal-footer border-top bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-light border rounded-3 fw-medium" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-3 fw-medium px-4">Crear Cupón</button>
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
                text: 'No podrás revertir esto.',
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
