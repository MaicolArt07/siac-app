@extends('adminlte::page')

@section('plugins.Sweetalert2', true)
@section('title', 'Historia Pago')
@livewireStyles

@section('content_header')
    <h1 class="fw-bold font-italic">Historia Pago</h1>
@stop

@section('content')
    @livewire('HistorialPagoLivewire')
@stop

@livewireScripts

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script src='{{asset('vendor/js/helpers.js')}}'></script>
    <script>
        notificacion();
        confirmacion('delete', 'destroy');
    </script>
@stop