<div class="field"><label for="code">Código</label><input id="code" name="code" value="{{ old('code', $resource?->code) }}" required></div>
<div class="field"><label for="name">Nombre</label><input id="name" name="name" value="{{ old('name', $resource?->name) }}" required></div>
<div class="field"><label for="resource_type">Tipo</label><input id="resource_type" name="resource_type" value="{{ old('resource_type', $resource?->resource_type) }}" required></div>
<div class="field"><label for="location">Ubicación</label><input id="location" name="location" value="{{ old('location', $resource?->location) }}" required></div>
<div class="field"><label for="status">Estado</label><select id="status" name="status"><option value="available">Disponible</option><option value="maintenance">Mantenimiento</option><option value="out_of_service">Fuera de servicio</option></select></div>
<div class="field"><label for="description">Descripción</label><textarea id="description" name="description">{{ old('description', $resource?->description) }}</textarea></div>
<input type="hidden" name="is_active" value="1">
