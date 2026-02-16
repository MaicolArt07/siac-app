<div>
    <style>
        .card {
            margin-bottom: 20px;
        }
        .card-body {
            display: flex;
            align-items: center;
        }
        .icon-container {
            flex-shrink: 0;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.2);
            font-size: 1.5rem;
            margin-right: 15px;
        }
        .card-title {
            margin-bottom: 0;
            font-size: 1.25rem;
            font-weight: 500;
        }
        .card-value {
            font-size: 1rem;
            font-weight: 700;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-4">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <div class="icon-container">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <h5 class="card-title">Copropietarios</h5>
                            <p class="card-value">{{$cantidad_copropietarios}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <div class="icon-container">
                            <i class="fas fa-building"></i>
                        </div>
                        <div>
                            <h5 class="card-title">Residentes Totales</h5>
                            <p class="card-value">{{$residentes_totales}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-secondary">
                    <div class="card-body">
                        <div class="icon-container">
                            <i class="fas fa-paw"></i>
                        </div>
                        <div>
                            <h5 class="card-title">Mascotas</h5>
                            <p class="card-value">{{$total_mascotas}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <div class="icon-container">
                            <i class="fas fa-money-check-alt"></i>
                        </div>
                        <div>
                            <h5 class="card-title">Ingresos</h5>
                            <p class="card-value">{{$pago_realizados}}Bs</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <div class="icon-container">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <div>
                            <h5 class="card-title">Deudas pendientes</h5>
                            <p class="card-value">{{$pago_deuda}}Bs</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-white bg-danger">
                    <div class="card-body">
                        <div class="icon-container">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                        <div>
                            <h5 class="card-title">Egresos</h5>
                            <p class="card-value">{{$total_egresos}}Bs</p>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <a href="{{route("resiboPDF")}}">PDF</a> --}}
            {{-- <button wire:click="generatePDF">Generar PDF</button> --}}
            <div class="col-md-12 mt-4">
                <canvas id="myChart" width="400" height="120"></canvas>
            </div>

    </div>
    @section('js')
    <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [
                    @foreach ($fechas as $fecha)
                        "{{ $fecha['mes'] }}/{{ $fecha['anio'] }}",
                    @endforeach
                ],
                datasets: [{
                    label: 'Pagos Realizados:',
                    data: [
                        @foreach ($ingresos_mes as $ingreso)
                            {{ $ingreso }},
                        @endforeach
                    ],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 159, 64, 0.7)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            fontColor: 'white' // Cambiar el color de los números del eje y
                        },
                        gridLines: {
                            color: 'rgba(255, 255, 255, 0.3)' // Cambiar el color de las líneas del eje y
                        }
                    }],
                    xAxes: [{
                        ticks: {
                            fontColor: 'white' // Cambiar el color de los números del eje x
                        },
                        gridLines: {
                            color: 'rgba(255, 255, 255, 0.3)' // Cambiar el color de las líneas del eje x
                        }
                    }]
                },
                legend: {
                    labels: {
                        fontColor: 'white' // Cambiar el color de las etiquetas de la leyenda
                    }
                }
            }
        });
    </script>
@stop
</div>