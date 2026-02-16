<div>
    {{-- **** CONTENEDOR **** --}}
    <div class="card">
        <div class="card-header">
            <div class="row d-flex justify-content-between">
                <div class="col-md-1">
                  
                </div>
                <div class="col-md-4">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-search"></i></span>
                        </div>
                        <input type="text" class="form-control" placeholder="Buscar..." aria-label="buscar" aria-describedby="basic-addon1" wire:model.live="search">
                    </div>
                </div>
            </div>

        </div>
        @if($copropietarios->count())
            <div class="card-body">
                <table class="table table-sm table-bordered table-hover ">
                    <thead class="thead-dark">
                        <tr class="text-center">
                            <th>#</th>
                            <th>COMPROPIETARIO</th>
                            <th>APARTAMENTO</th>
                            <th>GENERO</th>
                            <th>PAIS</th>
                            @can('funciones-cuentaCopropietario')
                                <th>ACCIONES</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($copropietarios as $copropietario)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{$copropietario->nombre}} {{$copropietario->apellido}} - {{$copropietario->ci}}</td>
                                <td>{{$copropietario->numero_apartamento}}</td>
                                <td class="text-center">{{$copropietario->genero}}</td>
                                <td class="text-center">{{$copropietario->pais}}</td>
                                @can('funciones-cuentaCopropietario')
                                    <td class="text-center">
                                        <a class="btn btn-warning"></a>
                                    </td>
                                @endcan
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{$copropietarios->links()}}
            </div>
        @else
            <div class="card-body">
                <strong class="text-center">No hay registros</strong>
            </div>
        @endif
    </div>
</div>
