<div>
    <div class="card">
        <div class="card-header">
            <div class="row d-flex justify-content-between">
                <div class="col-md-1">
                    @can('agregar-usuario')
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
        @if($users->count())
        <div class="card-body">
            <table class="table table-bordered table-sm table-hover">
                <thead class="thead-dark">
                    <tr class="text-center">
                        <th>#</th>
                        <th>USUARIO</th>
                        <th>EMAIL</th>
                        @can('funciones-usuario')
                        <th>ACCIÓN</th>
                        @endcan()
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{$user->name}}</td>
                            <td>{{$user->email}}</td>
                            @can('funciones-usuario')

                                <td class="text-center">
                                    @can('editar-usuario')
                                        <button class="btn btn-warning btn-sm" wire:click="edit({{ $user->id }})"><i class="fas fa-pencil-alt"></i></button>
                                    @endcan

                                    @can('eliminar-usuario')
                                        @if ($user->estado == 1)
                                            <button class="btn btn-danger btn-sm" wire:click="$dispatch('confirmDelete', {{ $user->id }})"><i class="fas fa-trash"></i></button>
                                        @else
                                            <button class="btn btn-primary btn-sm" wire:click="$dispatch('confirmDelete', {{ $user->id }})"><i class="fas fa-history"></i></button>
                                        @endif
                                    @endcan
                                    <button class="btn btn-primary btn-sm" wire:click="resetPassword({{$user->id}})"><i class="fas fa-redo"></i></button>
                                </td>

                            @endcan
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{$users->links()}}
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
                        <h4 class="modal-title font-italic font-weight-bolder">AGREGAR USUARIO</h4>
                        <button type="button" class=" btn btn-danger btn-sm" data-dismiss="modal" wire:click="closeModal">×</button>
                        {{--  --}}
                    </div>
        
                    <!-- Contenido del Modal -->
                    <div class="modal-body">
                        <form wire:submit="create">
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
                                
                              <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12 mt-2">
                                        <label for="">Condominios*:</label>
                                        <select class="form-control form-control" wire:model="usuarios.id_condominio" required>
                                            <option value="" selected>SELECCIONAR</option>
                                            @foreach ($condominios as $condominio)
                                                <option value="{{$condominio->id}}">{{$condominio->nombre}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-12 mt-2">
                                        <label class="form-label fw-bold">Usuario*:</label>
                                        <input type="email" class="form-control" wire:model="usuarios.email" required>
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <label class="form-label fw-bold">Contraseña*:</label>
                                        <input type="password" class="form-control" wire:model="password" required>
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <label class="form-label fw-bold">Confirmar*:</label>
                                        <input type="password" class="form-control @if($password === $confirmPassword && $confirmPassword !== '') border-success @else border-danger @endif" wire:model.live="confirmPassword" required>
                                    </div>
                                </div>
                              </div>
                            </div>
                            {{-- @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif --}}
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
                            @if($password === $confirmPassword && $confirmPassword !== '')
                                <div class="row d-flex justify-content-end mt-4">
                                    <x-button class="btn-success btn-sm">Guardar</x-button>
                                </div>
                            @endif
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
                        <h4 class="modal-title">EDITAR PERSONA</h4>
                        <button type="button" class=" btn btn-danger btn-sm" data-dismiss="modal" wire:click="closeModal">×</button>
                        {{--  --}}
                    </div>
        
                    <!-- Contenido del Modal -->
                    <div class="modal-body">
                        <form wire:submit="update">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="">Email:</label>
                                    <input class="form-control" type="text" wire:model="usuarios.email" required>
                                </div>
                                <div class="col-md-6 mt-4">
                                    {{-- <input type="checkbox" checked> --}}

                                    @foreach($roles as $rol)
                                    <br>
                                        {{-- <label>
                                            <input type="checkbox" {{ in_array($rol->id, $selectedRoles) ? "checked" : '' }} wire:model="selectedRoles.{{ $rol->id }}"  />
                                            <input type="checkbox" {{ in_array($rol->id, $selectedRoles) ? "checked" : '' }} wire:model="selectedRoles.{{ $rol->id }}">
                                            {{ $rol->name }}
                                        </label> --}}

                                        <label>
                                            <input type="radio" name="selectedRoles" value="{{ $rol->id }}" {{ $rol->id == $selectedRoles ? "checked" : '' }} wire:model="selectedRoles">
                                            {{ $rol->name }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            {{-- @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif --}}
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
        @if ($openModalResetPassword)
        <!-- Modal -->
        <div class="modal" tabindex="-1" aria-labelledby="exampleModalLabel" style="display: block" aria-modal="true" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Encabezado del Modal -->
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title font-italic font-weight-bold">CAMBIAR CONTRASEÑA</h4>
                        <button type="button" class=" btn btn-danger btn-sm" data-dismiss="modal" wire:click="closeModal">×</button>
                        {{--  --}}
                    </div>
        
                    <!-- Contenido del Modal -->
                    <div class="modal-body">
                        <form wire:submit="updatePassword">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="">Contraseña:</label>
                                    <input class="form-control" type="password" wire:model="usuarios.password" required>
                                </div>
                            </div>
                            {{-- @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif --}}
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
