<div>
    <div class="d-flex justify-content-center align-items-center" style="height: 70vh;">
        <div class="card p-4" style="width: 30rem;">
            <div class="card-body">
                <form wire:submit="generateReporte">
                    <div class="form-group">
                        <label for="gestion">GESTIÓN*:</label>
                        <select class="form-control" wire:model="idGestion" wire:change="selectedGestion($event.target.value)" required>
                            <option value="">Seleccionar</option>
                            @foreach ($gestiones as $gestion)
                                <option value="{{ $gestion->id }}">{{ $gestion->gestion }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if (count($periodos) > 0)
                        <div class="form-group">
                            <label for="periodo">PERÍODO*:</label>
                            <select class="form-control" wire:model="periodo" required>
                                <option value="">Seleccionar</option>
                                @foreach ($periodos as $periodo)
                                <option value="{{ $periodo->id }}">{{ $periodo->sigla }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <button type="submit" class="btn btn-primary btn-block mt-4">Generar Reporte</button>
                </form>
            </div>
        </div>
    </div>
</div>
