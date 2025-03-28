@extends('layouts.app')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/postulation-cover.css') }}">
    <link rel="stylesheet" href="{{ asset('css/contact.css') }}">
@endpush

@section('content')
    <livewire:cover title="Información de contacto" />

    <div class="container contact">
        <h3>Información de contacto</h3>
        <div class="row-2">
            <div>
                <div>
                    <p><i class="fa-solid fa-location-dot"></i> {{ utf8_decode($setting->direccion) }}</p>
                    <p><i class="fa-solid fa-earth-africa"></i> {{ utf8_decode($setting->nombre) }}</p>
                </div>

               <div class="contact-information">
                    <div class="link">
                        <label for="">Email:</label>
                        <a href="mailto:{{ $setting->email }}">{{ $setting->email }}</a></a>
                    </div>

                    <div class="link">
                        <label for="">Teléfono:</label>
                        <a href="tel:{{ utf8_decode($setting->telefono) }}"> {{ utf8_decode($setting->telefono) }}</a>
                    </div>

                    <div class="links">
                        <label for="">Síguenos:</label>
                        <div class="social_media">
                            <img src="{{ env('FTP_URL', $setting->url) . 'imagenes/' . $setting->logo_inferior }}" class="logo" alt="">

                            <div class="links">
                                @if($setting->facebook != '')
                                    <a href="{{ $setting->facebook }}" target="_blank" rel="noopener noreferrer" class="facebook"><i class="fa-brands fa-facebook-f"></i></a>
                                @endif

                                @if($setting->instagram != '')
                                    <a href="{{ $setting->instagram }}" target="_blank" rel="noopener noreferrer" class="instagram"><i class="fa-brands fa-instagram"></i></a>
                                @endif

                                @if($setting->twitter != '')
                                    <a href="{{ $setting->twitter }}" target="_blank" rel="noopener noreferrer" class="twitter"><i class="fa-brands fa-twitter"></i></a>
                                @endif

                                @if($setting->linkedin != '')
                                    <a href="{{ $setting->linkedin }}" target="_blank" rel="noopener noreferrer" class="linkedin"><i class="fa-brands fa-linkedin-in"></i></a>
                                @endif

                                @if($setting->whatsapp != '')
                                    <a href="{{ $setting->whatsapp }}" target="_blank" rel="noopener noreferrer" class="whatsapp"><i class="fa-brands fa-whatsapp"></i></a>
                                @endif
                            </div>
                        </div>
                    </div>
               </div>
            </div>
            <div>
                <img src="{{ env('FTP_URL', $setting->url) . 'imagenes/' . $setting->logo }}" class="logo" alt="">
            </div>
        </div>
    </div>
@endsection
