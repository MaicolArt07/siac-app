<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-center {
            text-align: center;
        }
    </style>
    <title>{{ request()->query('title') }}</title>
</head>
<body>
    @php
        $idCopropietarios = request()->query('idCopropietario');

        $copropietario = DB::table('v_copropietario')->where('id', $idCopropietarios)->first();
        // dd($idCopropietario);

        // print_r($copropietario);
        $fullName = $copropietario->nombre ." ". $copropietario->apellido;

        $total = 0;
        $contador = 1;

    @endphp
    <h1 style="text-align:center; font-weight:400">Recibo</h1>

    @if($copropietario)
        <table>
            <thead>
                <tr>
                    <td><b>NOMBRE: </b>{{ strtoupper($fullName) }}</th>
                    <td><b>CI: </b>{{ $copropietario->ci }}</th>
                </tr>
            </thead>
        </table>
    @endif
<br>
    <table>
        <tr>
            <th class="text-center">#</th>
            <th class="text-center">DESCRIPCIÓN</th>
            <th class="text-center">TIPO</th>
            <th class="text-center">TOTAL (Bs)</th>
        </tr>

        {{-- <p>{{ request()->query('deudas')}}</p> --}}
        {{-- <p>{{ $idCopropietario}}</p> --}}

        @if(request()->query('deudas'))
            @foreach(explode(',', request()->query('deudas')) as $deudaId)
            {{-- <p>{{ $deudaId}}</p> --}}

               @php
                    // $deudaIds = intval($deudaIds);
                    $deuda = DB::table('v_deuda')->where('id_pago', $deudaId)->first();
                    

                @endphp
                 @if($deuda)
                    @php
                        $total += $deuda->debe;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $contador++ }}</td>
                        <td>{{ $deuda->descripcion }}</td>
                        <td class="text-center">DEUDA</td>
                        <td class="text-center">{{ $deuda->debe }} Bs</td>
                    </tr>
                @endif
            @endforeach 
        @endif

        @if(request()->query('articulos'))
            @foreach(explode(',', request()->query('articulos')) as $articuloId)
                @php
                    $articulo = DB::table('v_articulo')->where('id', $articuloId)->first();
                @endphp
                @if($articulo)
                    @php
                        $total += $articulo->monto;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $contador++ }}</td>
                        <td>{{ $articulo->descripcion }}</td>
                        <td class="text-center">PAGO</td>
                        <td class="text-center">{{ $articulo->monto }} Bs</td>
                    </tr>
                @endif
            @endforeach
        @endif

        <tr>
            <th class="text-center" colspan="3">TOTAL A PAGAR</th>
            <th class="text-center">{{ $total }} Bs</th>
        </tr>
    </table>

    <p style="text-align:right;">Fecha de emisión: {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>

    <table style="width:100%; margin-top:50px;">
        <tr>
            <td class="text-center">
                ______________________<br>
                Firma del Administrador
            </td>
            <td class="text-center">
                ______________________<br>
                Firma del Copropietario
            </td>
        </tr>
    </table>
</body>
</html>
