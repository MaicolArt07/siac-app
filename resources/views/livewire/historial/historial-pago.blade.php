<div>
    {{-- **** CONTENEDOR **** --}}
    <div class="card">
        <div class="card-header">
            <div class="row d-flex justify-content-between">
                <div class="col-md-1">
                    {{-- @can('agregar-pago')
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
        @if($pagos->count())
        <div class="card-body">
            <table class="table table-sm table-bordered table-hover ">
                <thead class="thead-dark">
                    <tr class="text-center">
                        <th>#</th>
                        <th>COPROPIETARIO</th>
                        <th>DESCRIPCION</th>
                        <th>PERIODO</th>
                        <th>GESTION</th>
                        <th>DEBE</th>
                        <th>HABER</th>
                        @can('funciones-pago')
                            <th>ACCIONES</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pagos as $pago)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{$pago->nombre}} {{$pago->apellido}} - {{$pago->ci}}</td>
                            <td>{{$pago->descripcion}}</td>
                            <td>{{$pago->periodo}}</td>
                            <td>{{$pago->gestion}}</td>
                            <td>{{$pago->debe}}</td>
                            <td>{{$pago->haber}}</td>

                            @can('funciones-pago')
                                <td class="text-center">
                                    @can('eliminar-pago')
                                        @if ($pago->estado == 1)
                                            <button class="btn btn-danger btn-sm" wire:click="$dispatch('confirmDelete', {{ $pago->id }})"><i class="fas fa-trash"></i></button>
                                        @else
                                            <button class="btn btn-primary btn-sm" wire:click="$dispatch('confirmDelete', {{ $pago->id }})"><i class="fas fa-history"></i></button>
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
            {{$pagos->links()}}
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
                        <h4 class="modal-title font-italic font-weight-bold">AGREGAR PABELLON</h4>
                        <button type="button" class=" btn btn-danger btn-sm" data-dismiss="modal" wire:click="closeModal">×</button>
                    </div>
        
                    <!-- Contenido del Modal -->
                    <div class="modal-body">
                        <form wire:submit="created">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="">Pabellon*:</label>
                                    <input class="form-control" type="text" wire:model="pabellon.numero_pabellon" required>
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
                          <h4 class="modal-title font-italic font-weight-bold">EDITAR PABELLON</h4>
                          <button type="button" class=" btn btn-danger btn-sm" data-dismiss="modal" wire:click="closeModal">×</button>
                      </div>
          
                      <!-- Contenido del Modal -->
                      <div class="modal-body">
                          <form wire:submit="update">
                              <div class="row">
                                  <div class="col-md-12">
                                      <label for="">Pabellon*:</label>
                                      <input class="form-control" type="text" wire:model="pabellon.numero_pabellon" required>
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
