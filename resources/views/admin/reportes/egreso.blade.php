@extends('adminlte::page')

@section('plugins.Sweetalert2', true)
@section('title', 'Reporte Egreso')
@livewireStyles

@section('content_header')
    <h1 class="fw-bold font-italic">REPORTE EGRESO</h1>
@stop

@section('content')
@livewire('ReporteEgresoLivewire')

@stop

@livewireScripts

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script src='{{ asset('vendor/js/helpers.js') }}'></script>
    <script>
        // notificacion();
        // confirmacion('delete', 'destroy');
        Livewire.on('pdfGenerated', pdfData => {
            console.log(pdfData);

            let url = pdfData[0].url;
            let gestion = pdfData[0].gestion;
            let periodo = pdfData[0].periodo;

            window.open(`${url}?gestion=${gestion}&periodo=${periodo}`, '_blank');
        });
    </script>
@stop
