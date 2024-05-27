@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1> <strong> ORDENES </strong> </h1>
@stop

@section('content')


@livewire('form-create-order');


@stop

@section('css')
@stop

@section('js')
@stop
