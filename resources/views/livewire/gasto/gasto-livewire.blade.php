<div>

    {{-- **** CONTENEDOR **** --}}
        <div class="card">
            <div class="card-header">
                <div class="row d-flex justify-content-between">
                    <div class="col-md-1">
                        @can('agregar-gasto')
                            <button class="btn btn-success btn-md" wire:click="$toggle('openModalNew')"><i class="fas fa-plus-square"></i></button>
                        @endcan
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
            @if($gastos->count())
                <div class="card-body">
                    <table class="table table-sm table-bordered table-hover ">
                        <thead class="thead-dark">
                            <tr class="text-center">
                                <th>#</th>
                                <th>NOMBRE</th>
                                <th>TOTAL (Bs)</th>
                                <th>SIGLA</th>
                                <th>GESTIÓN</th>
                                @can('funciones-gasto')
                                    <th>ACCIONES</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($gastos as $gasto)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{$gasto->descripcion}}</td>
                                    <td>{{$gasto->monto}}Bs</td>
                                    <td>{{$gasto->sigla}}</td>
                                    <td>{{$gasto->gestion}}</td>
                                    @can('funciones-gasto')
                                        <td class="text-center">
                                            @can('editar-gasto')
                                                <button class="btn btn-warning btn-sm" wire:click="edit({{ $gasto->id }})"><i class="fas fa-pencil-alt"></i></button>
                                            @endcan
                                            @can('eliminar-gasto')
                                                @if ($gasto->estado == 1)
                                                    <button class="btn btn-danger btn-sm" wire:click="$dispatch('confirmDelete', {{ $gasto->id }})"><i class="fas fa-trash"></i></button>
                                                @else
                                                    <button class="btn btn-primary btn-sm" wire:click="$dispatch('confirmDelete', {{ $gasto->id }})"><i class="fas fa-history"></i></button>
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
                    {{$gastos->links()}}
                </div>
            @else
                <div class="card-body">
                    <strong class="text-center">No hay registros</strong>
                </div>
            @endif
        </div>


    {{-- * MODAL --}}
    @if ($openModalNew)
        <!-- Modal -->
        <div class="modal" tabindex="-1" aria-labelledby="exampleModalLabel" style="display: block" aria-modal="true" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Encabezado del Modal -->
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title font-italic font-weight-bold">NUEVO GASTO</h4>
                        <button type="button" class=" btn btn-danger btn-sm" data-dismiss="modal" wire:click="closeModal">×</button>
                    </div>
        
                    <!-- Contenido del Modal -->
                    <div class="modal-body">
                        <form wire:submit="created">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="">GESTIÓN*:</label>
                                    <select class="form-control" wire:change="seleccionarGestion($event.target.value)" required>
                                        <option value="">Seleccionar</option>
                                        @foreach ($gestiones as $gestion)
                                            <option value="{{ $gestion->id }}">{{ $gestion->gestion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if (count($periodos) > 0)
                                <div class="col-md-12 mt-4">
                                    <label for="">PERIODO*:</label>
                                    <select class="form-control" wire:change="seleccionarPeriodo($event.target.value)" required>
                                        <option value="">Seleccionar</option>
                                        @foreach ($periodos as $periodo)
                                            <option value="{{$periodo->id}}">{{$periodo->sigla}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                                @if (count($articulos) > 0)
                                <div class="col-md-12 mt-4">
                                    <label for="">ARTÍCULO*:</label>
                                    <select class="form-control" wire:model="gasto.id_articulo"  required>
                                        <option value="">Seleccionar</option>
                                        @foreach ($articulos as $articulo)
                                            <option value="{{$articulo->id}}">{{$articulo->descripcion}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif

                                <div class="col-md-12 mt-4 validate">
                                    @if ($errors->any())
                                    <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="row d-flex justify-content-end mt-4">
                                <x-button class="btn-success btn-sm">Guardar</x-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif


    
    {{-- * MODAL --}}
    @if ($openModalEdit)
        <!-- Modal -->
        <div class="modal" tabindex="-1" aria-labelledby="exampleModalLabel" style="display: block" aria-modal="true" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Encabezado del Modal -->
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title font-italic font-weight-bold">EDITAR GASTO</h4>
                        <button type="button" class=" btn btn-danger btn-sm" data-dismiss="modal" wire:click="closeModal">×</button>
                    </div>
        
                    <!-- Contenido del Modal -->
                    <div class="modal-body">
                        <form wire:submit="update">
                            <div class="row">
                                <div class="col-md-12 mt-4">
                                    <label for="">ARTÍCULO*:</label>
                                    <select class="form-control" wire:model="gasto.id_articulo"  required>
                                        <option value="">Seleccionar</option>
                                        @foreach ($articulosEdit as $articuloEdit)
                                            <option value="{{$articuloEdit->id}}">{{$articuloEdit->descripcion}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-12 mt-4 validate">
                                    @if ($errors->any())
                                    <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="row d-flex justify-content-end mt-4">
                                <x-button class="btn-success btn-sm">Guardar</x-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
