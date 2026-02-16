@extends('adminlte::page')

@section('plugins.Sweetalert2', true)
@section('title', 'Pago')
@livewireStyles

@section('content_header')
    <h1 class="fw-bold font-italic">Pago</h1>
@stop

@section('content')
    @livewire('PagoLivewire')
@stop
@livewireScripts

{{-- @section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop --}}

@section('js')
    <script src='{{asset('vendor/js/helpers.js')}}'></script>
    <script>
        notificacion();
        confirmacion('delete', 'destroy');
        // Livewire.on('pdfGenerated', pdfUrl => {
        //     window.open(pdfUrl, '_blank');
        // });
        Livewire.on('pdfGenerated', pdfData => {
            // console.log(pdfData);

            let url = pdfData[0].url;
            let title = pdfData[0].title;
            let deudas = pdfData[0].deudas.join(',');
            let articulos = pdfData[0].articulos.join(',');
            let idCopropietario = pdfData[0].idCopropietario;


            // console.log(idCopropietario);

            window.open(`${url}?title=${title}&deudas=${deudas}&articulos=${articulos}&idCopropietario=${idCopropietario}`, '_blank');
        });
    </script>
@stop