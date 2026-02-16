@extends('adminlte::page')

@section('plugins.Sweetalert2', true)
@section('title', 'Vehiculo')
@livewireStyles

@section('content_header')
    <h1 class="font-italic font-weight-bolder">Vehiculo</h1>
@stop

@section('content')
    @livewire('VehiculoLivewire')
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