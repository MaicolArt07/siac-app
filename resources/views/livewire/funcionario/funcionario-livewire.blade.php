<div>
    {{-- **** CONTENEDOR **** --}}
            <div class="card">
                <div class="card-header">
                    <div class="row d-flex justify-content-between">
                        <div class="col-md-1">
                            @can('agregar-funcionario')
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
                @if($funcionarios->count())
                    <div class="card-body">
                        <table class="table table-sm table-bordered table-hover ">
                            <thead class="thead-dark">
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>FUNCIONARIO</th>
                                    <th>CI</th>
                                    <th>CARGO</th>
                                    <th>SALARIO</th>
                                    @can('funciones-funcionario')
                                        <th>ACCIONES</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($funcionarios as $funcionario)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{$funcionario->persona}}</td>
                                        <td>{{$funcionario->ci}}</td>
                                        <td>{{$funcionario->cargo}}</td>
                                        <td>{{$funcionario->salario}}Bs</td>
                                        @can('funciones-funcionario')
                                            <td class="text-center">
                                                @can('editar-funcionario')
                                                    <button class="btn btn-warning btn-sm" wire:click="edit({{ $funcionario->id }})"><i class="fas fa-pencil-alt"></i></button>
                                                @endcan
                                                @can('eliminar-funcionario')
                                                    @if ($funcionario->estado == 1)
                                                        <button class="btn btn-danger btn-sm" wire:click="$dispatch('confirmDelete', {{ $funcionario->id }})"><i class="fas fa-trash"></i></button>
                                                    @else
                                                        <button class="btn btn-primary btn-sm" wire:click="$dispatch('confirmDelete', {{ $funcionario->id }})"><i class="fas fa-history"></i></button>
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
                        {{$funcionarios->links()}}
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
                    <h4 class="modal-title font-italic font-weight-bold">NUEVO FUNCIONARIO</h4>
                    <button type="button" class="btn btn-danger btn-sm buttonCerrarModal" data-dismiss="modal" wire:click="closeModal">×</button>
                </div>
    
                <!-- Contenido del Modal -->
                <div class="modal-body">
                    <form wire:submit="created">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" wire:model="obtenerIdPersona" required>
                                <label for="">Buscar Persona*:</label>
                                <input type="text" class="form-control buscar" placeholder="Buscar..." aria-label="Buscador" wire:model.live="searchPersona" 
                                @if($selectedPersona) disabled @endif>
                            </div>
                            <div class="col-md-12 rounded p-3 mt-2" id="contenedorBuscador">
                                @if(isset($personas) && strlen($searchPersona) > 0)
                                    @if($personas->isNotEmpty())
                                        <table class="table table-bordered table-sm table-hover">
                                            <thead class="bg-secondary">
                                                <tr class="text-center">
                                                    <th>NOMBRE</th>
                                                    <th>CI</th>
                                                    <th>ACCION</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($personas as $persona)
                                                    <tr class="persona-row" wire:key="persona-{{$persona->id}}" data-id="{{$persona->id}}" @if($selectedPersona && $obtenerIdPersona != $persona->id) style="display: none;" @endif>
                                                        <td>{{ $persona->nombre }} {{ $persona->apellido }}</td>
                                                        <td class="text-center">{{ $persona->ci }}</td>
                                                        <td class="text-center">
                                                            <a wire:click="seleccionarPersona({{ $persona->id }})" class="btn @if($obtenerIdPersona == $persona->id) btn-danger @else btn-outline-success @endif btn-sm botones ">
                                                                @if($obtenerIdPersona == $persona->id)
                                                                    Cancelar
                                                                @else
                                                                    Seleccionar
                                                                @endif
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <p class="text-center">Ningun dato encontrado.</p>
                                    @endif
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="">Cargo*:</label>
                                <select wire:model="funcionario.id_cargo" class="form-control form-control-sm" required>
                                    <option value="" selected>Seleccionar</option>
                                    @foreach ($cargos as $cargo)
                                        <option value='{{$cargo->id}}'>{{$cargo->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="">Salario*:</label>
                                <input type="number" min="1" wire:model="funcionario.salario" class="form-control form-control-sm" required>
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

            {{-- * MODAL --}}
               @if ($openModalEdit)
               <!-- Modal -->
               <div class="modal bd-example-modal-lg" tabindex="-1" aria-labelledby="exampleModalLabel" style="display: block" aria-modal="true" role="dialog">
                   <div class="modal-dialog modal-lg">
                       <div class="modal-content">
           
                           <!-- Encabezado del Modal -->
                           <div class="modal-header bg-primary">
                               <h4 class="modal-title font-italic font-weight-bold">EDITAR FUNCIONARIO</h4>
                               <button type="button" class=" btn btn-danger btn-sm" data-dismiss="modal" wire:click="closeModal">×</button>
                           </div>
               
                           <!-- Contenido del Modal -->
                           <div class="modal-body">
                               <form wire:submit="update">
                                   <div class="row">
                                    <div class="col-md-12">
                                        <input type="hidden" wire:model="obtenerIdPersona" required>
                                        <label for="">Buscar Persona*:</label>
                                        <input type="text" class="form-control buscar" placeholder="Buscar..." aria-label="Buscador" wire:model.live="searchPersona" 
                                        @if($selectedPersona) disabled @endif>
                                    </div>
                                    <div class="col-md-12 rounded p-3 mt-2" id="contenedorBuscador">
                                        @if(isset($personas) && strlen($searchPersona) > 0)
                                            @if($personas->isNotEmpty())
                                                <table class="table table-bordered table-sm table-hover">
                                                    <thead class="bg-secondary">
                                                        <tr class="text-center">
                                                            <th>NOMBRE</th>
                                                            <th>CI</th>
                                                            <th>ACCION</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($personas as $persona)
                                                            <tr class="persona-row" wire:key="persona-{{$persona->id}}" data-id="{{$persona->id}}" @if($selectedPersona && $obtenerIdPersona != $persona->id) style="display: none;" @endif>
                                                                <td>{{ $persona->nombre }} {{ $persona->apellido }}</td>
                                                                <td class="text-center">{{ $persona->ci }}</td>
                                                                <td class="text-center">
                                                                    <a wire:click="seleccionarPersona({{ $persona->id }})" class="btn @if($obtenerIdPersona == $persona->id) btn-danger @else btn-outline-success @endif btn-sm botones ">
                                                                        @if($obtenerIdPersona == $persona->id)
                                                                            Cancelar
                                                                        @else
                                                                            Seleccionar
                                                                        @endif
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @else
                                                <p class="text-center">Ningun dato encontrado.</p>
                                            @endif
                                        @endif
                                    </div>
                                       <div class="col-md-6">
                                           <label for="">Cargo*:</label>
                                           <select wire:model="funcionario.id_cargo" class="form-control form-control-sm" required>
                                               <option value="" selected>Seleccionar</option>
                                               @foreach ($cargos as $cargo)
                                                   <option value='{{$cargo->id}}'>{{$cargo->nombre}}</option>
                                               @endforeach
                                           </select>
                                       </div>
                                       <div class="col-md-6">
                                           <label for="">Salario*:</label>
                                           <input type="number" min="1" wire:model="funcionario.salario" class="form-control form-control-sm" required>
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

    {{-- **** FIN CONTENEDOR **** --}}
    </div>
    
    