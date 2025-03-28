@extends('layouts.app')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/postulation-cover.css') }}">
@endpush

@section('content')
    <livewire:cover title="Sobre Nosotros" />

    <div class="container">
        {!! utf8_decode($setting->nosotros) !!}
    </div>
@endsection
