@extends('layouts.app')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/postulation-cover.css') }}">
@endpush

@section('content')
    <livewire:cover title="¡Te damos la bienvenida a nuestro portal de empleos!" />

    <div class="container">
        {!! utf8_decode($setting->politica_privacidad) !!}
    </div>
@endsection
