<div class="mb-3">
    <label class="form-label fw-semibold text-muted small">Código</label>
    <input type="text" class="form-control bg-light rounded-3" name="codigo" value="<?= htmlspecialchars($cupon['codigo'] ?? '') ?>" placeholder="Ej. AGRO10" maxlength="50" required>
</div>
<div class="row g-3">
    <div class="col-6">
        <label class="form-label fw-semibold text-muted small">Descuento</label>
        <input type="number" class="form-control bg-light rounded-3" name="descuento" value="<?= htmlspecialchars($cupon['descuento'] ?? '') ?>" min="0" step="0.01" required>
    </div>
    <div class="col-6">
        <label class="form-label fw-semibold text-muted small">Tipo</label>
        <select class="form-select bg-light rounded-3" name="tipo" required>
            <option value="Porcentaje" <?= (($cupon['tipo'] ?? 'Porcentaje') === 'Porcentaje') ? 'selected' : '' ?>>Porcentaje (%)</option>
            <option value="Fijo" <?= (($cupon['tipo'] ?? '') === 'Fijo') ? 'selected' : '' ?>>Valor fijo ($)</option>
        </select>
    </div>
</div>
<div class="row g-3 mt-1">
    <div class="col-6"><label class="form-label fw-semibold text-muted small">Fecha de inicio</label><input type="datetime-local" class="form-control bg-light rounded-3" name="fecha_inicio" value="<?= !empty($cupon['fecha_inicio']) ? date('Y-m-d\TH:i', strtotime($cupon['fecha_inicio'])) : '' ?>"></div>
    <div class="col-6"><label class="form-label fw-semibold text-muted small">Fecha de fin</label><input type="datetime-local" class="form-control bg-light rounded-3" name="fecha_fin" value="<?= !empty($cupon['fecha_fin']) ? date('Y-m-d\TH:i', strtotime($cupon['fecha_fin'])) : '' ?>"></div>
</div>
<?php if ($cupon): ?><div class="form-check form-switch mt-4"><input class="form-check-input" type="checkbox" role="switch" name="estado" id="estado<?= $cupon['id_cupon'] ?>" <?= $cupon['estado'] ? 'checked' : '' ?>><label class="form-check-label fw-semibold" for="estado<?= $cupon['id_cupon'] ?>">Cupón activo</label></div><?php endif; ?>