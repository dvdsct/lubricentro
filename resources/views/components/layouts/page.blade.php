@extends('adminlte::page')

@section('title', $title ?? 'Rocket')

@section('content_header')
    @if (!empty($header))
        <h1><strong> {{ $header }} </strong></h1>
    @endif
@endsection

@section('content')
    {{ $slot }}
@endsection
