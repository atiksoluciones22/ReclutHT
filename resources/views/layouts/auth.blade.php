@extends('layouts.app', ['footer' => false])

@push('head')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('content')
    <div class="auth">
        <div class="cover"></div>

        <div class="form">
            @yield('form')
        </div>
    </div>
@endsection
