@extends('adminlte::page')

@section('title', 'Stock - Rocket')


@section('content')

    @livewire('preview-stock')

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop
