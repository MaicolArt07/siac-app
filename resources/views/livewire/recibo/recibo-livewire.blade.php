<div>
    {{-- **** CONTENEDOR **** --}}
            <div class="card">
                <div class="card-header">
                    <div class="row d-flex justify-content-between">
                        <div class="col-md-1">
                            {{-- @can('agregar-persona')
                                <button class="btn btn-success btn-md" wire:click="$toggle('openModalNew')"><i class="fas fa-plus-square"></i></button>
                            @endcan --}}
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
                @if($recibos->count())
                <div class="card-body">
                    <table class="table table-sm table-bordered table-hover ">
                        <thead class="thead-dark">
                            <tr class="text-center">
                                <th>#</th>
                                <th>NOMBRE</th>
                                <th>APELLIDO</th>
                                <th>CI</th>
                                <th>APARTAMENTO</th>
                                <th>FECHA</th>
                                @can('funciones-recibo')
                                    <th>ACCIONES</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recibos as $recibo)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{$recibo->nombre}}</td>
                                    <td>{{$recibo->apellido}}</td>
                                    <td>{{$recibo->ci}}</td>
                                    <td>{{$recibo->numero_apartamento}}</td>
                                    <td>{{$recibo->fecha_recibo}}</td>
                                    @can('funciones-recibo')
                                        <td class="text-center">
                                            @can('mostrar-generar-recibo')
                                                <a class="btn btn-primary btn-sm" href="{{$recibo->link}}" target="_blank"><i class="fas fa-external-link-alt"></i></a>
                                            @endcan
                                            @can('eliminar-recibo')
                                                @if ($recibo->estado == 1)
                                                    <button class="btn btn-danger btn-sm" wire:click="$dispatch('confirmDelete', {{ $recibo->id }})"><i class="fas fa-trash"></i></button>
                                                @else
                                                    <button class="btn btn-primary btn-sm" wire:click="$dispatch('confirmDelete', {{ $recibo->id }})"><i class="fas fa-history"></i></button>
                                                @endif
                                            @endcan
                                        </td>
                                    @endcan
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{$recibos->links()}}
                </div>
                @else
                    <div class="card-body">
                        <strong class="text-center">No hay registros</strong>
                    </div>
                @endif
            </div>