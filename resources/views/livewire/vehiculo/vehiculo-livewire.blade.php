<div>

    {{-- **** CONTENEDOR **** --}}
        <div class="card">
            <div class="card-header">
                <div class="row d-flex justify-content-between">
                    <div class="col-md-1">
                        @can('agregar-vehiculo')
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
            @if($vehiculos->count())
                <div class="card-body">
                    <table class="table table-sm table-bordered table-hover ">
                        <thead class="thead-dark">
                            <tr class="text-center">
                                <th>#</th>
                                <th>COPROPIETARIO</th>
                                <th>NUMERO ESTACIONAMIENTO</th>
                                <th>COLOR</th>
                                <th>MARCA</th>
                                <th>PLACA</th>
                                </th>
                                @can('funciones-vehiculo')
                                    <th>ACCIONES</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($vehiculos as $vehiculo)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{$vehiculo->nombre}} {{$vehiculo->apellido}} - {{$vehiculo->ci}}</td>
                                    <td>{{$vehiculo->numero_estacionamiento}}</td>
                                    <td>{{$vehiculo->color}}</td>
                                    <td>{{$vehiculo->marca}}</td>
                                    <td>{{$vehiculo->placa}}</td>
                                    @can('funciones-vehiculo')
                                        <td class="text-center">
                                            @can('editar-vehiculo')
                                                <button class="btn btn-warning btn-sm" wire:click="edit({{ $vehiculo->id }})"><i class="fas fa-pencil-alt"></i></button>
                                            @endcan
                                            @can('eliminar-vehiculo')
                                                @if ($vehiculo->estado == 1)
                                                    <button class="btn btn-danger btn-sm" wire:click="$dispatch('confirmDelete', {{ $vehiculo->id }})"><i class="fas fa-trash"></i></button>
                                                @else
                                                    <button class="btn btn-primary btn-sm" wire:click="$dispatch('confirmDelete', {{ $vehiculo->id }})"><i class="fas fa-history"></i></button>
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
                    {{$vehiculos->links()}}
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
                 <h4 class="modal-title font-italic font-weight-bold">NUEVO VEHICULO</h4>
                 <button type="button" class="btn btn-danger btn-sm buttonCerrarModal" data-dismiss="modal" wire:click="closeModal">×</button>
             </div>
 
             <!-- Contenido del Modal -->
             <div class="modal-body">
                 <form wire:submit="created">
                     <div class="row">
                         <div class="col-md-12">
                             <input type="hidden" wire:model="obtenerIdCopropietario" required>
                             <label for="">Buscar Copropietario*:</label>
                             <input type="text" class="form-control buscar" placeholder="Buscar..." aria-label="Buscador" wire:model.live="searchCopropietario" 
                             @if($selectedCopropietario) disabled @endif>
                         </div>
                         <div class="col-md-12 rounded p-3 mt-2" id="contenedorBuscador">
                             @if(isset($copropietarios) && strlen($searchCopropietario) > 0)
                                 @if($copropietarios->isNotEmpty())
                                     <table class="table table-bordered table-sm table-hover">
                                         <thead class="bg-secondary">
                                             <tr class="text-center">
                                                 <th>NOMBRE</th>
                                                 <th>CI</th>
                                                 <th>ACCION</th>
                                             </tr>
                                         </thead>
                                         <tbody>
                                             @foreach($copropietarios as $copropietario)
                                                 <tr class="copropietario-row" wire:key="copropietario-{{$copropietario->id}}" data-id="{{$copropietario->id}}" @if($selectedCopropietario && $obtenerIdCopropietario != $copropietario->id) style="display: none;" @endif>
                                                     <td>{{ $copropietario->nombre }} {{ $copropietario->apellido }}</td>
                                                     <td class="text-center">{{ $copropietario->ci }}</td>
                                                     <td class="text-center">
                                                         <a wire:click="seleccionarCopropietario({{ $copropietario->id }})" class="btn @if($obtenerIdCopropietario == $copropietario->id) btn-danger @else btn-outline-success @endif btn-sm botones ">
                                                             @if($obtenerIdCopropietario == $copropietario->id)
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
                             <label for="">ESTACIONAMIENTO*:</label>
                             <select wire:model="vehiculo.id_estacionamiento" class="form-control form-control-sm" required>
                                 <option value="" selected>Seleccionar</option>
                                 @foreach ($estacionamientos as $estacionamiento)
                                     @if($estacionamiento->estado == 1)
                                         <option value='{{$estacionamiento->id}}'>{{$estacionamiento->numero_pabellon}} {{$estacionamiento->numero_estacionamiento}}</option>
                                     @else
                                         <option class="text-danger" value='{{$estacionamiento->id}}' disabled>{{$estacionamiento->numero_estacionamiento}} - OCUPADO</option>
                                     @endif
                                 @endforeach
                             </select>
                         </div>
                         <div class="col-md-4 mt-4">
                             <label for="">COLOR*:</label>
                             <input type="text" wire:model="vehiculo.color" class="form-control form-control-sm" required>
                         </div>
                         <div class="col-md-4 mt-4">
                             <label for="">MARCA*:</label>
                             <input type="text" wire:model="vehiculo.marca" class="form-control form-control-sm" value="0" required>
                         </div>
                         <div class="col-md-4 mt-4">
                            <label for="">PLACA*:</label>
                            <input type="text" wire:model="vehiculo.placa" class="form-control form-control-sm" value="0" required>
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
        <div class="modal" tabindex="-1" aria-labelledby="exampleModalLabel" style="display: block" aria-modal="true" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Encabezado del Modal -->
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title font-italic font-weight-bold">EDITAR VEHICULO</h4>
                        <button type="button" class=" btn btn-danger btn-sm" data-dismiss="modal" wire:click="closeModal">×</button>
                    </div>
        
                    <!-- Contenido del Modal -->
                    <div class="modal-body">
                        <form wire:submit="update">
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="hidden" wire:model="obtenerIdCopropietario" required>
                                    <label for="">Buscar Copropietario*:</label>
                                    <input type="text" class="form-control buscar" placeholder="Buscar..." aria-label="Buscador" wire:model.live="searchCopropietario" 
                                    @if($selectedCopropietario) disabled @endif>
                                </div>
                                <div class="col-md-12 rounded p-3 mt-2" id="contenedorBuscador">
                                    @if(isset($copropietarios) && strlen($searchCopropietario) > 0)
                                        @if($copropietarios->isNotEmpty())
                                            <table class="table table-bordered table-sm table-hover">
                                                <thead class="bg-secondary">
                                                    <tr class="text-center">
                                                        <th>NOMBRE</th>
                                                        <th>CI</th>
                                                        <th>ACCION</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($copropietarios as $copropietario)
                                                        <tr class="copropietario-row" wire:key="copropietario-{{$copropietario->id}}" data-id="{{$copropietario->id}}" @if($selectedCopropietario && $obtenerIdCopropietario != $copropietario->id) style="display: none;" @endif>
                                                            <td>{{ $copropietario->nombre }} {{ $copropietario->apellido }}</td>
                                                            <td class="text-center">{{ $copropietario->ci }}</td>
                                                            <td class="text-center">
                                                                <a wire:click="seleccionarCopropietario({{ $copropietario->id }})" class="btn @if($obtenerIdCopropietario == $copropietario->id) btn-danger @else btn-outline-success @endif btn-sm botones ">
                                                                    @if($obtenerIdCopropietario == $copropietario->id)
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
                                    <label for="">ESTACIONAMIENTO*:</label>
                                    <select wire:model="vehiculo.id_estacionamiento" class="form-control form-control-sm" required>
                                        <option value="" selected>Seleccionar</option>
                                        @foreach ($estacionamientos as $estacionamiento)
                                            @if($estacionamiento->estado == 1)
                                                <option value='{{$estacionamiento->id}}'>{{$estacionamiento->numero_estacionamiento}}</option>
                                            @else
                                                <option class="text-danger" value='{{$estacionamiento->id}}' disabled>{{$estacionamiento->numero_estacionamiento}} - OCUPADO</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mt-4">
                                    <label for="">COLOR*:</label>
                                    <input type="text" wire:model="vehiculo.color" class="form-control form-control-sm" required>
                                </div>
                                <div class="col-md-4 mt-4">
                                    <label for="">MARCA*:</label>
                                    <input type="text" wire:model="vehiculo.marca" class="form-control form-control-sm" value="0" required>
                                </div>
                                <div class="col-md-4 mt-4">
                                   <label for="">PLACA*:</label>
                                   <input type="text" wire:model="vehiculo.placa" class="form-control form-control-sm" value="0" required>
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
