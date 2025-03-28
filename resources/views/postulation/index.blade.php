@extends('layouts.app')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/postulation-list.css') }}">
@endpush

@section('content')
    <livewire:cover :title="utf8_decode($setting->texto_slide)" :subtitle="$setting->texto_slide2" />

    @include('includes.success-message')

    <livewire:postulation-list :setting="$setting" />
@endsection

