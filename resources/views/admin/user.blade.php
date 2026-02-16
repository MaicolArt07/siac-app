@extends('adminlte::page')

@section('plugins.Sweetalert2', true)
@section('title', 'Usuario')
@livewireStyles

@section('content_header')
    <h1 class="font-italic font-weight-bolder">Usuario</h1>
@stop

@section('content')
    @livewire('UserLivewire')
@stop
@livewireScripts

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script src='{{asset('vendor/js/helpers.js')}}'></script>
    <script>
        notificacion();
        confirmacion('delete', 'eliminar');
    </script>
@stop