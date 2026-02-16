@extends('adminlte::page')

@section('plugins.Sweetalert2', true)
@section('title', 'Tipo pago')
@livewireStyles

@section('content_header')
    <h1 class="fw-bold font-italic">TIPO PAGO</h1>
@stop

@section('content')
    @livewire('TipoPagoLivewire')
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