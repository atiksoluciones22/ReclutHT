@extends('layouts.panel')

<!-- TENGO QUE VOLVER EL LISTADO EN LINEA -->

@section('section')
    @include('includes.success-message')

    <div class="container">
        <h2 class="title">Todas tus postulaciones</h2>
    </div>

    <livewire:postulation-list :setting="$setting" :byUser="true" />
@endsection
