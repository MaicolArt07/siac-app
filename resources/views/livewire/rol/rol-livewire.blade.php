<div>
    {{-- <button class="btn btn-success mr-2" wire:click="$toggle('openModalNew')"><i class="fas fa-plus-square"></i></button> --}}
    <div class="card">
        <div class="card-header">
            <div class="row d-flex justify-content-between">
                <div class="col-md-1">
                    @can('agregar-rol')
                    <button class="btn btn-success btn-md" wire:click="$toggle('openModalNew')"><i class="fas fa-plus-square"></i></button>
                    @endcan

                </div>
                <div class="col-md-4">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-search"></i></span>
                        </div>
                        <input type="search" class="form-control" placeholder="Buscar..." aria-label="buscar" aria-describedby="basic-addon1" wire:model.live="search">
                    </div>
                </div>
            </div>

        </div>
        <div class="card-body">
            <table class="table table-sm table-bordered table-hover">
                <thead class="thead-dark">
                    <tr class="text-center">
                        <th>#</th>
                        <th>Rol</th>
                        @can('funciones-rol')
                        <th>ACCIONES</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $rol)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{$rol->name}}</td>
                            @can('funciones-tipopago')
                                <td class="text-center">
                                    @can('editar-tipopago')
                                    <button class="btn btn-warning btn-sm" wire:click="edit({{ $rol->id }})"><i class="fas fa-pencil-alt"></i></button>
                                    @endcan
                                    @can('eliminar-tipopago')
                                        @if($rol->estado == 1)
                                            <button class="btn btn-danger btn-sm" wire:click="$dispatch('confirmDelete', {{ $rol->id }})"><i class="fas fa-trash"></i></button>
                                        @else
                                            <button class="btn btn-primary btn-sm" wire:click="$dispatch('confirmDelete', {{ $rol->id }})"><i class="fas fa-history"></i></button>
                                        @endif
                                    @endcan
                                </td>
                            @endcan
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>



        
    {{-- * MODAL --}}
    @if ($openModalNew)
        <!-- Modal -->
        <div class="modal" tabindex="-1" aria-labelledby="exampleModalLabel" style="display: block" aria-modal="true" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content" style="width: 120%">

                    <!-- Encabezado del Modal -->
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title">NUEVO ROL</h4>
                        <button type="button" class=" btn btn-danger btn-sm" data-dismiss="modal" wire:click="closeModal">×</button>
                    </div>
        
                    <!-- Contenido del Modal -->
                    <div class="modal-body">
                        <form wire:submit="created">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="">Nombre Rol:</label>
                                    <input class="form-control" type="text" wire:model="rol.name" required>
                                </div>

                                <div class="col-md-12 mt-4">
                                    <input class="form-control" type="search" wire:model.live="searchPermission" placeholder="Buscar Permiso...">
                                </div>

                                <div class="col-md-12">
                                    <table class="table table-sm table-bordered mt-4">
                                        <thead class="thead-dark">
                                            <tr class="text-center">
                                                <th>#</th>
                                                <th>PERMISO</th>
                                                <th>DESCRIPCION</th>
                                                <th>MODULO</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($permissions->count())
                                                @foreach ($permissions as $permission)
                                                    <tr>
                                                        <td class="text-center">{{ $loop->iteration }}</td>
                                                        <th>{{$permission->name}}</th>
                                                        <th>{{$permission->description}}</th>
                                                        {{-- <th>{{$permission->nombre}}</th> --}}
                                                        <th><input type="checkbox" wire:model="selectedPermission.{{ $permission->id }}"></th>
                                                    </tr>
                                                @endforeach
                                            @else
                                            <tr>
                                                <td class="text-center" colspan="4">Ningun registro encontrado</td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
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
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card-footer">
                                        {{$permissions->links()}}
                                    </div>
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
                <div class="modal-content" style="width: 120%">
                    <!-- Encabezado del Modal -->
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title">NUEVO ROL</h4>
                        <button type="button" class=" btn btn-danger btn-sm" data-dismiss="modal" wire:click="closeModal">×</button>
                        {{--  --}}
                    </div>
        
                    <!-- Contenido del Modal -->
                    <div class="modal-body">
                        <form wire:submit="update">
                            <div class="row">

                                <div class="col-md-12">
                                    <label for="">Nombre Rol:</label>
                                    <input class="form-control form-control-sm" type="text" wire:model="rol.name" required>
                                </div>

                                <div class="col-md-12 mt-4">
                                    <input class="form-control" type="search" wire:model.live="searchPermission" placeholder="Buscar Permiso...">
                                </div>

                                <div class="col-md-12">
                                    <table class="table table-sm table-bordered mt-4">
                                        <thead class="thead-dark">
                                            <tr class="text-center">
                                                <th>#</th>
                                                <th>PERMISO</th>
                                                <th>DESCRIPCION</th>
                                                <th>MODULO</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($permissions->count())
                                                @foreach ($permissions as $permission)
                                                    <tr>
                                                        <td class="text-center">{{ $loop->iteration }}</td>
                                                        <th>{{$permission->name}}</th>
                                                        <th>{{$permission->description}}</th>
                                                        {{-- <th>{{$permission->nombre}}</th> --}}
                                                        <th><input type="checkbox" wire:model="selectedPermission.{{ $permission->id }}"></th>
                                                    </tr>
                                                @endforeach
                                            @else
                                            <tr>
                                                <td class="text-center" colspan="4">Ningun registro encontrado</td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                                    <div class="col-md-12" style="  margin: 0;
                                    display: flex;
                                    justify-content: center;">
                                        {{$permissions->links()}}
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
                                @if($permissions->count() > 0)
                                    <x-button class="btn-success btn-sm">Actualizar</x-button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
    {{--  ** FIN DEL CIERRE --}}
</div>
