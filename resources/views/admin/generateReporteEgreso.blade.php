<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            max-width: 100%;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
        }
        .title-section {
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            margin: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 12px;
        }
        th {
            background-color: #151c16;
            color: white;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .total-row td {
            font-weight: bold;
        }
        h1 {
            text-align: center;
            color: #333333;
            margin-bottom: 10px;
        }
        footer {
            text-align: center;
            margin-top: 30px;
            color: #666666;
        }
    </style>
    <title>REPORTE EGRESO</title>
</head>
<body>
    @php
        $gestion = request()->query('gestion');
        $periodo = request()->query('periodo');
    @endphp

    <h1>REPORTE GASTO</h1>

    <div class="container">
        <table style="font-size: 14px; width: 100%;">
            <thead>
                <tr>
                    <th style="text-align: center">DETALLE</th>
                    <th style="text-align: center">PERIODO</th>
                    <th style="text-align: center">FECHA</th>
                    <th style="text-align: center">MONTO</th>
                </tr>
            </thead>
            <tbody>
                
                @php
                    $pagos = DB::table('v_gasto')->where('id_gestion', $gestion)->where('estado', 1)->where('id_periodo', $periodo)->where('monto', '>', 0)->get();
                    $total = 0;
                @endphp

                @foreach($pagos as $pago)
                    <tr>
                        <td>{{ $pago->descripcion }}</td>
                        <td class="text-center">{{ $pago->periodo }}</td>
                        <td>{{ $pago->fecha_gasto }}</td>
                        <td class="text-right">{{ number_format($pago->monto, 2) }}Bs</td>
                    </tr>
                    <?php $total += $pago->monto; ?>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="3" class="text-right">Total:</td>
                    <td class="text-right">{{ number_format($total, 2) }}Bs</td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>
</html>
