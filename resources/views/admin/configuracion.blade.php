@extends('layouts.app')
@section('title', 'Configuración del Sistema')
@section('content')

<div class="d-flex flex-column gap-4" style="max-width: 800px; margin: 0 auto; width: 100%;">

    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h3 class="fw-bold text-dark mb-1">Ajustes Generales</h3>
            <span class="text-muted small">Configura la información básica de tu empresa.</span>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert">
            <i class="fa-solid fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4 p-md-5">
            <form action="{{ route('configuracion.store') }}" method="POST">
                @csrf

                <h5 class="fw-bold text-dark mb-4 pb-2 border-bottom">Datos de la Empresa</h5>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Nombre de la Empresa <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="fa-solid fa-building text-primary"></i></span>
                            <input type="text" name="nombre_empresa" class="form-control rounded-end-3 bg-light border-0 py-2"
                                value="{{ old('nombre_empresa', $config->nombre_empresa ?? '') }}"
                                required placeholder="Ej. AgriStock Inc.">
                        </div>
                        @error('nombre_empresa')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">NIT / Documento</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="fa-solid fa-id-card text-primary"></i></span>
                            <input type="text" name="nit" class="form-control rounded-end-3 bg-light border-0 py-2"
                                value="{{ old('nit', $config->nit ?? '') }}"
                                placeholder="Ej. 123456789-0">
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label text-muted small fw-bold">Dirección Principal</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="fa-solid fa-map-location-dot text-primary"></i></span>
                            <input type="text" name="direccion" class="form-control rounded-end-3 bg-light border-0 py-2"
                                value="{{ old('direccion', $config->direccion ?? '') }}"
                                placeholder="Dirección de la empresa">
                        </div>
                    </div>
                </div>

                <h5 class="fw-bold text-dark mb-4 pb-2 border-bottom">Información de Contacto</h5>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Teléfono / WhatsApp</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="fa-solid fa-phone text-primary"></i></span>
                            <input type="text" name="telefono" class="form-control rounded-end-3 bg-light border-0 py-2"
                                value="{{ old('telefono', $config->telefono ?? '') }}"
                                placeholder="Ej. +1 234 567 8900">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Correo Electrónico</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="fa-solid fa-envelope text-primary"></i></span>
                            <input type="email" name="email" class="form-control rounded-end-3 bg-light border-0 py-2"
                                value="{{ old('email', $config->email ?? '') }}"
                                placeholder="correo@empresa.com">
                        </div>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-3 mt-5 pt-3 border-top">
                    <button type="reset" class="btn btn-light rounded-3 px-4 fw-medium border shadow-sm text-secondary">
                        Deshacer Cambios
                    </button>
                    <button type="submit" class="btn btn-primary rounded-3 px-5 fw-medium shadow-sm"
                        style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%); border: none;">
                        <i class="fa-solid fa-floppy-disk me-2"></i>Guardar Ajustes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
