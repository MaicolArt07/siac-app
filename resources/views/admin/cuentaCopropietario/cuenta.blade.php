@extends('adminlte::page')


@section('title', 'Cargo')
@livewireStyles

@section('content_header')
    <h1 class="fw-bold font-italic">CUENTA COPROPIETARIO</h1>
@stop

@section('content')
    @livewire('CuentaCopropietarioLivewire')
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