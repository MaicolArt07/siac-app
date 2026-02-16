<div>
{{-- **** CONTENEDOR **** --}}
        <div class="card">
            <div class="card-header">
                <div class="row d-flex justify-content-between">
                    <div class="col-md-1">
                        @can('agregar-persona')
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
            @if($personas->count())
            <div class="card-body">
                <table class="table table-sm table-bordered table-hover ">
                    <thead class="thead-dark">
                        <tr class="text-center">
                            <th>#</th>
                            <th>NOMBRE</th>
                            <th>APELLIDO</th>
                            <th>CI</th>
                            <th>GÉNERO</th>
                            <th>PAIS</th>
                            <th>CORREO</th>
                            <th>TELEFONO</th>
                            @can('funciones-persona')
                                <th>ACCIONES</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($personas as $persona)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ucwords(strtolower($persona->nombre))}}</td>
                                <td>{{ucwords(strtolower($persona->apellido))}}</td>
                                @if (!empty($persona->complemento_ci))
                                    <td>{{$persona->ci ."-".$persona->complemento_ci}}</td>
                                @else
                                    <td>{{$persona->ci}}</td>
                                @endif
                                <td>{{ucwords(strtolower($persona->genero))}}</td>
                                <td>{{ucwords(strtolower($persona->pais))}}</td>
                                <td>{{strtolower($persona->correo)}}</td>
                                <td>{{$persona->telefono}}</td>
                                @can('funciones-persona')
                                    <td class="text-center">
                                        @can('editar-persona')
                                            <button class="btn btn-warning btn-sm" wire:click="edit({{ $persona->id }})"><i class="fas fa-pencil-alt"></i></button>
                                        @endcan
                                        @can('eliminar-persona')
                                            @if ($persona->estado == 1)
                                                <button class="btn btn-danger btn-sm" wire:click="$dispatch('confirmDelete', {{ $persona->id }})"><i class="fas fa-trash"></i></button>
                                            @else
                                                <button class="btn btn-primary btn-sm" wire:click="$dispatch('confirmDelete', {{ $persona->id }})"><i class="fas fa-history"></i></button>
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
                {{$personas->links()}}
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
        <div class="modal bd-example-modal-lg" tabindex="-1" aria-labelledby="exampleModalLabel" style="display: block" aria-modal="true" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <!-- Encabezado del Modal -->
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title font-italic font-weight-bold">NUEVA PERSONA</h4>
                        <button type="button" class=" btn btn-danger btn-sm" data-dismiss="modal" wire:click="closeModal">×</button>
                    </div>
        
                    <!-- Contenido del Modal -->
                    <div class="modal-body">
                        <form wire:submit="created">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Nombre*:</label>
                                    <input class="form-control form-control-sm" type="text" wire:model="persona.nombre" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Apellido*:</label>
                                    <input class="form-control form-control-sm" type="text" wire:model="persona.apellido" required>
                                </div>
                                <div class="col-md-3 mt-2">
                                    <label for="">CI*:</label>
                                    <input class="form-control form-control-sm" type="number" wire:model="persona.ci" min="0" required>
                                </div>
                                <div class="col-md-3 mt-2">
                                    <label for="">Complemento CI:</label>
                                    <input class="form-control form-control-sm" type="text" wire:model="persona.complemento_ci">
                                </div>
                                <div class="col-md-6 mt-2">
                                    <label for="">Correo*:</label>
                                    <input class="form-control form-control-sm" type="email" wire:model="persona.correo" required>
                                </div>
                                <div class="col-md-3 mt-2">
                                    <label for="">Teléfono*:</label>
                                    <input class="form-control form-control-sm" type="number" wire:model="persona.telefono" min="6" required>
                                </div>
                                <div class="col-md-3 mt-2">
                                    <label for="">Teléfono Ref:</label>
                                    <input class="form-control form-control-sm" type="number" wire:model="persona.telefono2" min="0">
                                </div>
                                <div class="col-md-6 mt-2">
                                    <label for="">Fecha Nacimiento*:</label>
                                    <input class="form-control form-control-sm" type="date" wire:model="persona.fecha_nac" required>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <label for="">País*:</label>
                                    <select class="form-control form-control-sm select2" wire:model="persona.id_pais" required>
                                        <option value="" selected>SELECCIONAR</option>
                                        @foreach ($paises as $pais)
                                            <option value="{{$pais->id}}">{{$pais->nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <label for="">Género*:</label>
                                    <select class="form-control form-control-sm select2" wire:model="persona.id_genero" required>
                                        <option value="" selected>SELECCIONAR</option>
                                        @foreach ($generos as $genero)
                                            <option value="{{$genero->id}}">{{$genero->nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12 mt-4">
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
    <div class="modal bd-example-modal-lg" tabindex="-1" aria-labelledby="exampleModalLabel" style="display: block" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <!-- Encabezado del Modal -->
                <div class="modal-header bg-primary">
                    <h4 class="modal-title font-italic font-weight-bold">EDITAR PERSONA</h4>
                    <button type="button" class=" btn btn-danger btn-sm" data-dismiss="modal" wire:click="closeModal">×</button>
                </div>
    
                <!-- Contenido del Modal -->
                <div class="modal-body">
                    <form wire:submit="update">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Nombre*:</label>
                                <input class="form-control form-control-sm" type="text" wire:model="persona.nombre" required>
                            </div>
                            <div class="col-md-6">
                                <label for="">Apellido*:</label>
                                <input class="form-control form-control-sm" type="text" wire:model="persona.apellido" required>
                            </div>
                            <div class="col-md-3 mt-2">
                                <label for="">CI*:</label>
                                <input class="form-control form-control-sm" type="number" wire:model="persona.ci" min="0" required>
                            </div>
                            <div class="col-md-3 mt-2">
                                <label for="">Complemento CI:</label>
                                <input class="form-control form-control-sm" type="text" wire:model="persona.complemento_ci">
                            </div>
                            <div class="col-md-6 mt-2">
                                <label for="">Correo*:</label>
                                <input class="form-control form-control-sm" type="email" wire:model="persona.correo"  required>
                            </div>
                            <div class="col-md-3 mt-2">
                                <label for="">Teléfono*:</label>
                                <input class="form-control form-control-sm" type="number" wire:model="persona.telefono" min="0" required>
                            </div>
                            <div class="col-md-3 mt-2">
                                <label for="">Teléfono Ref:</label>
                                <input class="form-control form-control-sm" type="number" wire:model="persona.telefono2" min="0">
                            </div>
                            <div class="col-md-6 mt-2">
                                <label for="">Fecha Nacimiento*:</label>
                                <input class="form-control form-control-sm" type="date" wire:model="persona.fecha_nac" required>
                            </div>
                            <div class="col-md-6 mt-2">
                                <label for="">Pais*:</label>
                                <select class="form-control form-control-sm select2" wire:model="persona.id_pais" required>
                                    <option value="" selected>SELECCIONAR</option>
                                    @foreach ($paises as $pais)
                                        <option value="{{$pais->id}}">{{$pais->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mt-2">
                                <label for="">Género*:</label>
                                <select class="form-control form-control-sm select2" wire:model="persona.id_genero" required>
                                    <option value="" selected>SELECCIONAR</option>
                                    @foreach ($generos as $genero)
                                        <option value="{{$genero->id}}">{{$genero->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12 mt-4">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif 
                                <div class="row d-flex justify-content-end mt-4">
                                    <x-button class="btn-success btn-sm">Guardar</x-button>
                                </div>
                            </div>
                            </form>
                        </div>
            
                        
                        
                    </div>
                </div>
            </div>
        @endif

        {{-- *** FIN MODAL AGREGAR PERSONA --}}
            </div>
        </div>
    </div>
{{-- **** FIN CONTENEDOR **** --}}
</div>

