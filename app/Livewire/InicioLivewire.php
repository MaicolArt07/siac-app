<?php

namespace App\Livewire;

use App\Models\Articulo;
use App\Models\Copropietario;
use App\Models\ExpensaControl;
use App\Models\Pago;
use App\Models\Periodo;
use Carbon\Carbon;
use Illuminate\Support\Carbon as SupportCarbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class InicioLivewire extends Component
{

    // public $total_egresos = 0;



    public function render()
    {

        $userCondominio = $this->UserIdCondominio();

        $date = Carbon::now();
        $mes_actual = $date->format('n');
        $anio_actual = $date->format('y');
        $gestion = $date->format('Y');
        $cantidad_copropietarios = Copropietario::count();
        
        $pago_realizados =  DB::table('v_pago')->where('periodo', $mes_actual)
                            ->where('sigla_gestion', $anio_actual)->where('estado', 1)->sum('haber'); 

        $residentes_totales = Copropietario::sum('cant_residentes');

        $total_mascotas = Copropietario::sum('cant_mascotas');
        $pago_deuda = DB::table('v_deuda')->where('periodo', $mes_actual)
                    ->where('gestion', $gestion)->where('estado', 1)->sum('debe');

        $total_egresos = DB::table('v_gasto')->where('periodo', $mes_actual)
                        ->where('gestion', $gestion)->where('estado', 1)->sum('monto');                                                                 

        $fechas = [];
        for ($i = 5; $i >= 0; $i--) {
            $fecha = $date->copy()->subMonths($i);
            $fechas[] = [
                'mes' => $fecha->format('n'),
                'anio' => $fecha->format('Y')
            ];
        }

        $ingresos_mes = [];
        foreach ($fechas as $fecha) {
            $ingresos_mes[] = DB::table('v_pago')
                ->where('periodo', $fecha['mes'])
                ->where('gestion', $fecha['anio'])
                ->where('estado', 1)
                ->sum('haber');
        }
        // $this->generarExpensaControl();

        // Generamos los articulos de acuerdo a la expensa control si el condominio es 1 ingavi

        // Consulta de los ultimos 6 meses de la gestion actual
        $this->generarCobroCondominioIngavi();
        return view('livewire.inicio-livewire', compact('cantidad_copropietarios', 'pago_realizados', 'residentes_totales', 'total_mascotas', 'pago_deuda', 'total_egresos','ingresos_mes','fechas'));
    }


    public function UserIdCondominio()
    {
        $userAuth = auth()->user();
        // Obtenemos el id del condominio del usuario para por defecto que se esta agregando un copropietario en ese condominio.
        $userCondominio = $userAuth->id_condominio;
        return $userCondominio;
    }

    // public function generarExpensaControl()
    // {
    //     $gestionActual = Carbon::now()->year; 
    //     $mes = Carbon::now()->month;

    //        // Verificar si ya existe un registro en control_historial para el mes y el año actual
    //        $historialExistente = DB::table('control_historial')
    //        ->whereYear('created_at', $gestionActual)
    //        ->whereMonth('created_at', $mes)
    //        ->where('tipo_historial', 2)
    //        ->exists();
   
    //    if ($historialExistente) {
    //        // Si ya existe un registro para el mes y año actual, no ejecutar el resto de la función
    //        return;
    //    }
    //     // Obtener el periodo actual

    //     $periodoActual = DB::table('v_periodo')->where('periodo', $mes)->where('gestion',$gestionActual)->first();
    //     $userCondominio = $this->UserIdCondominio();

    //     if($userCondominio == 1) // CONDOMINIO INGAVI
    //     {
    //         // Obtengo la expensa control de acuerdo al condominio
    //         $expensaControles = DB::table('v_expensa_control')
    //                         ->where('estado_control', 1)
    //                         ->where('id_condominio', $userCondominio)->get();

    //         // Validamos que exista algo
    //         if(isset($expensaControles))
    //         {
    //             // Empezamos a recorrer e insertar los controles como articulos
    //             foreach($expensaControles as $expensaControl)
    //             {

    //                 Articulo::create([
    //                     'id_periodo' => $periodoActual->id, 
    //                     'id_condominio' => $expensaControl->id_condominio,
    //                     'id_tipoarticulo' => 1, 
    //                     'descripcion' => $expensaControl->numero_apartamento, 
    //                     'monto' => $expensaControl->monto, 
    //                 ]);
    //             }

    //             // Insertamos un control de generador de cobros
    //             // Insertar el registro en control_historial
    //             DB::table('control_historial')->insert([
    //                 'tipo_historial' => 2,
    //                 'created_at' => Carbon::now(),
    //                 'updated_at' => Carbon::now(),
    //             ]);
    //         }
    //     }
    // }

    public function generarCobroCondominioIngavi()
    {
        $userCondominio = $this->UserIdCondominio();

         // Obtener el mes y el año actual
         $gestionActual = Carbon::now()->year; 
         $mes = Carbon::now()->month;
     
         // Verificar si ya existe un registro en control_historial para el mes y el año actual
         $historialExistente = DB::table('control_historial')
             ->whereYear('created_at', $gestionActual)
             ->whereMonth('created_at', $mes)
             ->where('tipo_historial', 1)
             ->exists();
     
         if ($historialExistente) {
             // Si ya existe un registro para el mes y año actual, no ejecutar el resto de la función
             return;
         }
     
         // Obtener a todos los copropietarios activos
         $copropietarios = DB::table('v_copropietario')->where('estado', 1)
                            ->where('id_condominio', $userCondominio)
                            ->get();
     
         // Insetaremos cada articulo como deuda a cada copropietario encontrado como activo
         foreach ($copropietarios as $copropietario) {
             $idCopropietario = $copropietario->id;
             
            // Obtenemos todos los articulos que se cobrara en la gestion actual con el periodo actual
            $articulos = DB::table("v_articulo")->where('gestion', $gestionActual)
                            ->where('periodo', $mes)
                            ->where('id_condominio', $userCondominio)
                            ->where('descripcion', $copropietario->numero_apartamento)
                            ->where('estado', 1)
                            ->where('id_tipoarticulo', 1)
                            ->get();
             foreach ($articulos as $articulo) {
                 // Insertar el pago y obtener el último ID insertado
                 $idPago = DB::table('pago')->insertGetId([
                     'id_copropietario' => $idCopropietario,
                     'id_articulo' => $articulo->id,
                     'id_tipopago' => 1,
                     'descripcion' => "COBRO DE EXPENSA",
                     'debe' => $articulo->monto,
                     'haber' => 0
                 ]);
         
                 // Verificar si la inserción fue exitosa
                 if ($idPago) {
                     DB::table('deuda')->insert([
                         'id_pago' => $idPago,
                     ]);
                 }
             }
         }
     
         // Insertar el registro en control_historial
         DB::table('control_historial')->insert([
             'tipo_historial' => 1,
             'created_at' => Carbon::now(),
             'updated_at' => Carbon::now(),
         ]);
    }

    public function generarCobros()
    {
        // Obtener el mes y el año actual
        $gestionActual = Carbon::now()->year; 
        $mes = Carbon::now()->month;
    
        // Verificar si ya existe un registro en control_historial para el mes y el año actual
        $historialExistente = DB::table('control_historial')
            ->whereYear('created_at', $gestionActual)
            ->whereMonth('created_at', $mes)
            ->where('tipo_historial', 1)
            ->exists();
    
        if ($historialExistente) {
            // Si ya existe un registro para el mes y año actual, no ejecutar el resto de la función
            return;
        }
    
        // Obtener a todos los copropietarios activos
        $copropietarios = Copropietario::where('estado', 1)->get();
    
        // Obtenemos todos los articulos que se cobrara en la gestion actual con el periodo actual
        $articulos = DB::table("v_articulo")->where('gestion', $gestionActual)
                                            ->where('periodo', $mes)
                                            ->where('estado', 1)
                                            ->where('id_tipoarticulo', 1)
                                            ->get();
    
        // Insetaremos cada articulo como deuda a cada copropietario encontrado como activo
        foreach ($copropietarios as $copropietario) {
            $idCopropietario = $copropietario->id;
            
            foreach ($articulos as $articulo) {
                // Insertar el pago y obtener el último ID insertado
                $idPago = DB::table('pago')->insertGetId([
                    'id_copropietario' => $idCopropietario,
                    'id_articulo' => $articulo->id,
                    'id_tipopago' => 1,
                    'descripcion' => "COBRO DE EXPENSA",
                    'debe' => $articulo->monto,
                    'haber' => 0
                ]);
        
                // Verificar si la inserción fue exitosa
                if ($idPago) {
                    DB::table('deuda')->insert([
                        'id_pago' => $idPago,
                    ]);
                }
            }
        }
    
        // Insertar el registro en control_historial
        DB::table('control_historial')->insert([
            'tipo_historial' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
    

}
