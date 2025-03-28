@extends('layouts.panel', ['nav' => false, 'welcome' => false])

@push('head')
    <link rel="stylesheet" href="{{ asset('css/exam.css') }}">
    <link rel="stylesheet" href="{{ asset('css/timer.css') }}">
@endpush

@section('section')
    <div class="container container-test">
        <div class="detail ">
            <h1 class="title">{{ $title }}</h1>
            <p>Debe completar todas las preguntas antes de que se termine el tiempo.</p>
        </div>

        <livewire:exam.form :exam="$exam" :examcode="$examCode" :type="$type" />
    </div>
@endsection
