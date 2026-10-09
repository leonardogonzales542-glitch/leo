<div class="mb-3">
    <label class="form-label fw-semibold text-muted small">Código</label>
    <input type="text" class="form-control bg-light rounded-3" name="codigo"
        value="{{ old('codigo', $cupon->codigo ?? '') }}"
        placeholder="Ej. AGRO10" maxlength="50" required>
</div>
<div class="row g-3">
    <div class="col-6">
        <label class="form-label fw-semibold text-muted small">Descuento</label>
        <input type="number" class="form-control bg-light rounded-3" name="descuento"
            value="{{ old('descuento', $cupon->descuento ?? '') }}"
            min="0" step="0.01" required>
    </div>
    <div class="col-6">
        <label class="form-label fw-semibold text-muted small">Tipo</label>
        <select class="form-select bg-light rounded-3" name="tipo" required>
            <option value="Porcentaje" {{ old('tipo', $cupon->tipo ?? 'Porcentaje') === 'Porcentaje' ? 'selected' : '' }}>Porcentaje (%)</option>
            <option value="Fijo" {{ old('tipo', $cupon->tipo ?? '') === 'Fijo' ? 'selected' : '' }}>Valor fijo ($)</option>
        </select>
    </div>
</div>
<div class="row g-3 mt-1">
    <div class="col-6">
        <label class="form-label fw-semibold text-muted small">Fecha de inicio</label>
        <input type="date" class="form-control bg-light rounded-3" name="fecha_inicio"
            value="{{ old('fecha_inicio', isset($cupon->fecha_inicio) ? $cupon->fecha_inicio->format('Y-m-d') : '') }}">
    </div>
    <div class="col-6">
        <label class="form-label fw-semibold text-muted small">Fecha de fin</label>
        <input type="date" class="form-control bg-light rounded-3" name="fecha_fin"
            value="{{ old('fecha_fin', isset($cupon->fecha_fin) ? $cupon->fecha_fin->format('Y-m-d') : '') }}">
    </div>
</div>
<div class="form-check form-switch mt-4">
    <input class="form-check-input" type="checkbox" role="switch" name="estado"
        id="estado{{ $cupon->id_cupon ?? 'new' }}"
        {{ old('estado', $cupon->estado ?? true) ? 'checked' : '' }}>
    <label class="form-check-label fw-semibold" for="estado{{ $cupon->id_cupon ?? 'new' }}">Cupón activo</label>
</div>
