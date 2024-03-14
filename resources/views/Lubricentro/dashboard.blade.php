@extends('adminlte::page')

@section('title', 'Dashboard')

<!-- @section('content_header')
    <h1>Dashboard</h1>
@stop -->

@section('content')

    <div class="row">


        <div class="col-8">
            @livewire('prev-turnos')
            @livewire('form-create-order')
        </div>
        <div class="col-4">
            @livewire('preview-stock')

            <div class="info-box mb-3 bg-warning">
                <span class="info-box-icon"><i class="fas fa-tag"></i></span>
                <div class="info-box-content">
                <span class="info-box-text">Ingresar</span>
                <span class="info-box-number"></span>
                </div>

                </div>

            <div class="info-box mb-3 bg-danger">
                <span class="info-box-icon"><i class="fas fa-cloud-download-alt"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Registrar Gasto</span>
                    <span class="info-box-number"></span>
                </div>

            </div>
        </div>
    </div>





@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        console.log('Hi!');
    </script>
@stop
