<?php

namespace App\Livewire;


use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\Articulo;
use App\Models\TipoPago;
use App\Models\Deuda;
use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Support\Facades\DB;
use App\Livewire\Form\CuentaCopropietarioForm;
use App\Models\Copropietario;
use App\Models\CuentaApartamento;
use App\Models\numeroBanca;
use App\Models\Pago;
use Carbon\Carbon;

class PagoLivewire extends Component
{

    protected $paginationTheme = 'bootstrap';
    public $search;
    public $searchCopropietario;
    public $selectedCopropietario = false;
    public $obtenerIdCopropietario = "";

    public $openModalNew = false;
    public $openModalEdit = false;
    public $articulosPago = [];
    public $pagoDisabled = true;
    public $tipopago;
    public $fecha;
    public $numeroBanca;
    public $numeroTransferencia;
    // public $deudas = [];

    public $saldo = 0;

    public $idCuentaCopropietarioArreglo = [];
    public $totalPago = 0;
    public $efectivo = 0;
    public $selectedDeudasArreglo = [];
    public $selectedArticulos = [];
    public $selectedArticulo = null;
    public $selectedArticulosDetalles = [];

    public function resetAttribute()
    {
    $this->reset(['openModalNew', 'openModalEdit', 'search', 'selectedCopropietario', 'obtenerIdCopropietario', 'articulosPago', 'pagoDisabled', 'idCuentaCopropietarioArreglo', 'totalPago', 'efectivo', 'selectedDeudasArreglo', 'selectedArticulos', 'selectedArticulo', 'selectedArticulosDetalles', 'searchCopropietario', 'tipopago', 'fecha', 'numeroBanca']);
    }


    // public function mount($deudas)
    // {
    //     $this->deudas = $deudas;
    // }

    // public function updatedSelectedDeudas($value, $index)
    // {
    //     if (!empty($this->deudasCopropietario) && array_key_exists($index, $this->deudasCopropietario)) {
    //         if ($value) {
    //             $this->totalPago += $this->deudasCopropietario[$index]['debe'];
    //         } else {
    //             $this->totalPago -= $this->deudasCopropietario[$index]['debe'];
    //         }
    //     }
    // }

    public function guardarSeleccion()
    {
        // Verificamos si el articulo es Pago a cuenta para habilitar las nuevas funcionalidades
        $PAGO_CUENTA = "PAGO A CUENTA";
        $articulo = DB::table("v_articulo")->where('id', $this->selectedArticulo)->where('estado', 1)->first();

        
        if(trim($PAGO_CUENTA) == trim($articulo->descripcion))
        {
            
        }

        if ($this->selectedArticulo && !in_array($this->selectedArticulo, $this->selectedArticulos)) {
            $articulo = DB::table("v_articulo")->where('id', $this->selectedArticulo)->where('estado', 1)->first();
            if ($articulo) {
                $this->selectedArticulos[] = $this->selectedArticulo;
                $this->selectedArticulosDetalles[] = $articulo;
                $this->totalPago += $articulo->monto;
            }
        }
    }
    
    public function eliminarArticulo($idArticulo)
    {
        if (($key = array_search($idArticulo, $this->selectedArticulos)) !== false) {
            $articulo = $this->selectedArticulosDetalles[$key];
            unset($this->selectedArticulos[$key]);
            unset($this->selectedArticulosDetalles[$key]);
            $this->selectedArticulos = array_values($this->selectedArticulos); // Reindex the array
            $this->selectedArticulosDetalles = array_values($this->selectedArticulosDetalles); // Reindex the array
            $this->totalPago -= $articulo->monto;
        }
    }
    
    public function selectedDeudas($debe, $idCuentaCopropietario, $isChecked)
    {
        if ($isChecked) {
            $this->selectedDeudasArreglo[] = $debe;
            $this->idCuentaCopropietarioArreglo[] = $idCuentaCopropietario;
        } else {
            $key = array_search($debe, $this->selectedDeudasArreglo);
            if ($key !== false) {
                unset($this->selectedDeudasArreglo[$key]);
                unset($this->idCuentaCopropietarioArreglo[$key]);
                $this->selectedDeudasArreglo = array_values($this->selectedDeudasArreglo); // Reindex the array
                $this->idCuentaCopropietarioArreglo = array_values($this->idCuentaCopropietarioArreglo); // Reindex the array
            }
        }
        $this->totalPago = array_sum($this->selectedDeudasArreglo) + array_sum(array_column($this->selectedArticulosDetalles, 'monto'));
    }
    

    public function closeModal()
    {
        $this->resetAttribute();
        // $this->resetErrorBag();
        $this->resetValidation();
    }

    public function seleccionarCopropietario($id)
    {
        $this->obtenerIdCopropietario = $id;
        $this->selectedCopropietario = !$this->selectedCopropietario;
        $this->pagoDisabled = !$this->pagoDisabled;

        // Al seleccionar al copropietario obtener el apartamento y calcular su saldo actual
        $copropietario = Copropietario::find($this->obtenerIdCopropietario);

        $saldoActualApartamento = DB::table('v_pago')
        ->where('id_apartamento', $copropietario->id_apartamento)
        ->select(
            DB::raw('SUM(debe) as total_debe'),
            DB::raw('SUM(haber) as total_haber')
        )
        ->first();

        $saldoTotal =  $saldoActualApartamento->total_haber - $saldoActualApartamento->total_debe;

        $this->saldo = $saldoTotal;

        if(!$this->selectedCopropietario)
        {
            $this->reset(['obtenerIdCopropietario', 'saldo']);
        }


    }

    function UserIdCondominio()
    {
        $userAuth = auth()->user();
        // Obtenemos el id del condominio del usuario para por defecto que se esta agregando un copropietario en ese condominio.
        $userCondominio = $userAuth->id_condominio;
        return $userCondominio;
    }

    public function render()
    {
        $userCondominio = $this->UserIdCondominio();

        $copropietarios = collect();

        if(!empty($this->searchCopropietario))
        {
            $copropietarios = DB::table('v_copropietario')
                                    ->where('estado', 1) 
                                    ->where('id_condominio', $userCondominio)
                                    ->where(function($query) {
                                        $query->where('nombre', 'like', '%' . $this->searchCopropietario . '%')
                                            ->orWhere('apellido', 'like', '%' . $this->searchCopropietario . '%')
                                            ->orWhere('ci', 'like', '%' . $this->searchCopropietario . '%')
                                            ->orWhere('numero_apartamento', 'like', '%' . $this->searchCopropietario . '%');
                                    })
                                    ->orderBy('id', 'desc')
                                    ->limit(5)
                                    ->get();
        }

        // OBTENER TODOS LOS ARTICULOS HABILITADOS DEL PERIODO ACTUAL
        $date = Carbon::now();
        $mes_actual = $date->format('n');
        $anio_actual = $date->format('y');
        $anio_gestion = $date->format('Y');

    
        $numeroApartamento = '';

        $deudas = collect();
        $deudaArticulos = collect();
        $pagoArticulos = collect();

        if(isset($this->obtenerIdCopropietario) && !empty($this->obtenerIdCopropietario))
        {
            $copropietario = DB::table('v_copropietario')->where('id', $this->obtenerIdCopropietario)->first();
            $numeroApartamento = $copropietario->numero_apartamento;
            $deudas = DB::table("v_deuda")->where('id_apartamento', $copropietario->id_apartamento)->where('estado', 1)->get();

          // VERIFICAMOS QUE NO EXISTE UN ARTICULO IGUAL DEL MODULO ACTUAL PARA EVITAR QUE COMPRE NUEVAMENTE EL MISMO ARTICULO
            $deudaArticulos = DB::table('v_deuda')
                            // ->where('id_copropietario', $this->obtenerIdCopropietario)
                            ->where('id_apartamento', $copropietario->id_apartamento)
                            // ->where('gestion', $anio_gestion)
                            // ->where('periodo', $mes_actual)
                            ->groupBy('id_articulo')
                            ->pluck('id_articulo');
            // Tiene que ser diferente al pago a cuenta ya que el pago a cuenta se realiza varias veses.....

            $pagoArticulos = DB::table('v_pago')
                            ->where('id_copropietario', $this->obtenerIdCopropietario)
                            ->where('id_tipoarticulo','!=', 1) 
                            ->groupBy('id_articulo')
                            ->pluck('id_articulo');
                            
        }

        $articulosExpensa = DB::table('v_articulo')
                        // ->where('periodo', $mes_actual)
                        ->where('nombre', $anio_actual)
                        ->where('id_tipoarticulo', 1)
                        ->where('descripcion', 'like', '%' .$numeroApartamento .'%')
                        ->where('id_condominio',$userCondominio)
                        ->whereNotIn('id', $deudaArticulos)
                        ->whereNotIn('id', $pagoArticulos)
                        ->groupBy('id')
                        ->get();

        $numeroApartamentos = DB::table('v_expensa_control')
                                ->where('estado_control', 1)
                                ->pluck('numero_apartamento')
                                ->toArray();
        
        $articulosPago = DB::table('v_articulo')
                        ->where('periodo', $mes_actual)
                        ->where('nombre', $anio_actual)
                        ->where('id_tipoarticulo', 1)
                        ->where('id_condominio', $userCondominio)
                        ->whereNotIn('id', $deudaArticulos)
                        ->whereNotIn('id', $pagoArticulos)
                        ->whereNotIn('descripcion', $numeroApartamentos) // Excluir apartamentos de expensasControl
                        ->groupBy('id')
                        ->get();

        
        $tipopagos = TipoPago::all();
        $numeroBancas = numeroBanca::all();

        // Combinar ambas colecciones en una sola
        $articulos = $articulosExpensa->merge($articulosPago);

        // Opcional: Eliminar duplicados si existen, basados en el campo 'id'
        $articulos = $articulos->unique('id');
                // ! Tenemos que buscar si el copropietario tiene deuda
                // Se buscara si el apartamento posee alguna deuda anterior sin importar el copropietario


        $articulosPagos = DB::table("v_articulo")->whereIn('id', $this->selectedArticulos)->where('estado', 1)->groupBy('id')->get();


        return view('livewire.pago.pago-livewire', compact('copropietarios', 'articulos', 'tipopagos', 'deudas', 'articulosPagos', 'numeroBancas'));
    }


    // Creacion una funcion que verique el saldo a cuenta que posee el apartamento para luego empezar pagar las deudas, si posee el saldo suficiente

    public function cuentaApartamentoPagarDeuda($id, $fecha)
    {
        // insertamos en la tabla cuenta_apartamento
        $PagoCuenta =  CuentaApartamento::created([
            'id_pago' => $id,
            'fecha' => $fecha
        ]);

        // Verificamos en la tabla cuentaApartamento todo el saldo activo que tenga.
        // ! No tocamos los datos de pago.
        if($PagoCuenta)
        {
            
        }
    }

    public function created()
    {
        // dd($this->numeroBanca);

        if(isset($this->idCuentaCopropietarioArreglo) && !empty($this->idCuentaCopropietarioArreglo))
        {
            foreach($this->idCuentaCopropietarioArreglo as $cuentaCopropietario)
            {
                $cuenta = Pago::find($cuentaCopropietario);
                
                    $cuentaCopro = Pago::create([
                        'id_copropietario' => $cuenta->id_copropietario, 
                        'id_numerobanca' => $this->numeroBanca,
                        'id_articulo' => $cuenta->id_articulo, 
                        'id_tipopago' => $this->tipopago,
                        'descripcion' => 'PAGO', 
                        'debe' => 0, 
                        'nro_transferencia' => $this->numeroTransferencia,
                        'fecha' => $this->fecha,
                        'haber' => $cuenta->efectivo, 
                    ]);

                    if($cuentaCopro)
                    {

                        $deudaCopropietario = Deuda::where('id_pago', $cuentaCopropietario)->where('estado', 1)->first();
                        $deudaCopropietario->estado = 0;
                        $deudaCopropietario->save(); 
                    }
            }

            //if($cuentaCopro)
            //{
               // $response = $cuentaCopro ? true : false;
                //$this->dispatch('notificar', message: $response);
                // $this->resetAttribute();
           // }
        }

        if(isset($this->selectedArticulos) && !empty($this->selectedArticulos))
        {
            foreach($this->selectedArticulos as $selectedArticulo)
            {
                // Obtener el monto del articulo establecido
                $articulo = Articulo::find($selectedArticulo);

                // $pago = $articulo->monto;

                // ** Revisamos si es un pago a cuenta para solo colocarlo al haber y no al debe.

                // * REALIZAMOS DOS INSERCIONES AL PAGO TANTO AL DEBE Y AL HABER
                for ($i=1; $i <= 2; $i++) 
                { 
                    if($i == 1)
                    {
                        if($articulo->id_tipoarticulo != 1)
                        {
                            $cuentaCopro = Pago::create([
                                'id_copropietario' => $this->obtenerIdCopropietario, 
                                'id_numerobanca' => $this->numeroBanca,
                                'id_articulo' => $selectedArticulo, 
                                'id_tipopago' => $this->tipopago,
                                'descripcion' => 'COBRO', 
                                'debe' => $this->efectivo, 
                                'nro_transferencia' => $this->numeroTransferencia,
                                'fecha' => $this->fecha,
                                'haber' => 0, 
                            ]);
                        }
            
                    }else{

                        $cuentaCopro = Pago::create([
                            'id_copropietario' => $this->obtenerIdCopropietario, 
                            'id_numerobanca' => $this->numeroBanca,
                            'id_articulo' => $selectedArticulo, 
                            'id_tipopago' => $this->tipopago,
                            'descripcion' => 'PAGO', 
                            'debe' => 0, 
                            'nro_transferencia' => $this->numeroTransferencia,
                            'fecha' => $this->fecha,
                            'haber' => $this->efectivo, 
                        ]);

                        // * Si el articulo es pago a cuenta insertamos en la tabla cuentaApartamento
                        if($articulo->id_tipoarticulo == 1 && $cuentaCopro)
                        {
                            CuentaApartamento::created([
                                'id_copropietario' => $this->obtenerIdCopropietario, 
                                'id_numerobanca' => $this->numeroBanca,
                                'id_articulo' => $selectedArticulo, 
                                'id_tipopago' => $this->tipopago,
                                'descripcion' => 'PAGO', 
                                'debe' => 0, 
                                'nro_transferencia' => $this->numeroTransferencia,
                                'fecha' => $this->fecha,
                                'haber' => $this->efectivo, 
                            ]);
                        }
                    }
                }

            }
        }

        if($cuentaCopro)
        {
            // dd($this->selectedArticulos);
            $this->generatePDF($this->idCuentaCopropietarioArreglo, $this->selectedArticulos, $this->obtenerIdCopropietario);
            
            // INSERTAMOS EL LINK EN LA TABLA RECIBO
            $response = $cuentaCopro ? true : false;
            $this->dispatch('notificar', message: $response);
            // $this->resetAttribute();
        }

    }

    public function generatePDF($arreglo_deudas, $arreglo_articulo, $idCopropietario)
    {

        $data = [
            'title' => 'PDF generado en Laravel',
            'deudas' => $arreglo_deudas,
            'articulos' => $arreglo_articulo,
            'idCopropietario' => $idCopropietario
        ];

        // dd($idCopropietario);
        // Construimos el link
        $pdfPath = 'resiboPDF'; // Cambiar el nombre del archivo PDF si es necesario

        $deudas = implode(',', $data['deudas']);
        $articulos = implode(',', $data['articulos']);
        $link = "{$pdfPath}?title={$data['title']}&deudas={$deudas}&articulos={$articulos}&idCopropietario={$idCopropietario}";


        // Insertamos el link en la tabla RECIBO
        DB::table('recibo')->insert([
            'id_copropietario' => $idCopropietario,
            'link' => $link
        ]);
        // dd($link);
        

        // Storage::put($pdfPath, $pdf->output());

        // Emitir evento para notificar al frontend
        $this->dispatch('pdfGenerated', [
            'url' => $pdfPath,
            'title' => $data['title'],
            'deudas' => $arreglo_deudas,
            'articulos' => $arreglo_articulo, 
            'idCopropietario' => $idCopropietario
        ]);
        // dd($idCopropietario);
        // $pdf = PDF::loadView('admin.resiboPDF', $data);

        $this->resetAttribute();

    }
}