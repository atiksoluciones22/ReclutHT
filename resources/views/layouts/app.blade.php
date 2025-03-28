<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <style>
        :root {
            --primary-color: {{ $setting->color1 }};
            --secondary-color: {{ $setting->color2 }};
            --tertiary-color: {{ $setting->color3 }};
            --quaternary-color: {{ $setting->color4 }};
            --url-cover: url({{ env('FTP_URL', $setting->url) . "imagenes/" . $setting->imagen_slide }});
        }
    </style>

    <script src="https://kit.fontawesome.com/19a1f7c413.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('css/postulation-cover.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/confirm-alert.css') }}">
    @stack('head')
</head>

<body>
    @livewireScripts

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <x-livewire-alert::scripts />

    <div id="loader">
        <div class="circle-loader">
            <svg class='spinner' viewbox='25 25 50 50'>
                <circle class='path' cx='50' cy='50' r='20' fill='none' stroke-width='2' stroke-miterlimit='10' ></circle>
            </svg>
        </div>
    </div>

    <header class="header">
        <svg class="mobile open-menu"  data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12"></path>
        </svg>

        <a href="{{ route('home') }}" class="logo" >
            <img src="{{ env('FTP_URL', $setting->url) . "imagenes/" . $setting->logo }}" alt="Logo" srcset="">
        </a>

        <ul>
            <li><a href="{{ route('home') }}">Empleos</a></li>
            <li><a href="{{ route('about') }}">Sobre Nosotros</a></li>
            <li><a href="{{ route('contact') }}">Contacto</a></li>
        </ul>

        <div class="flex">
            @auth

            <a href="{{ route('panel') }}" class="profile">
                <span>Perfil</span>
                <div>
                    {{ auth()->user()->first_letter }}
                </div>
            </a>

            @else
                <a href="{{ route('login') }}" class="button-link">
                    <svg data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"></path>
                    </svg>
                    Inicia sesión
                </a>
            @endauth
        </div>

    </header>

    <div id="app">
        @yield('content')
    </div>

    @if(!isset($footer))
        <footer class="footer">
            <div class="container">
                <div class="items">
                    <div class="links_interest">
                        <h3>Enlaces de interés</h3>
                        <ul>
                            <li>
                                <a href="{{ route('home') }}">
                                    <svg data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m5.25 4.5 7.5 7.5-7.5 7.5m6-15 7.5 7.5-7.5 7.5"></path>
                                    </svg>
                                    Empleos
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('about') }}">
                                    <svg data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m5.25 4.5 7.5 7.5-7.5 7.5m6-15 7.5 7.5-7.5 7.5"></path>
                                    </svg>
                                    Sobre Nosotros
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('privacy-policy') }}">
                                    <svg data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m5.25 4.5 7.5 7.5-7.5 7.5m6-15 7.5 7.5-7.5 7.5"></path>
                                    </svg>
                                    Privacidad y Protección de datos
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('login') }}">
                                    <svg data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m5.25 4.5 7.5 7.5-7.5 7.5m6-15 7.5 7.5-7.5 7.5"></path>
                                    </svg>
                                    Iniciar sesión
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('contact') }}">
                                    <svg data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m5.25 4.5 7.5 7.5-7.5 7.5m6-15 7.5 7.5-7.5 7.5"></path>
                                    </svg>
                                    Contacto
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="information_contact">
                        <div>
                            <h3>Información de contacto</h3>
                            <ul>
                                @if($setting->direccion != '')
                                    <li>
                                        <a href="#">
                                            <i class="fa-solid fa-location-dot"></i>
                                            {{ utf8_decode($setting->direccion) }}
                                        </a>
                                    </li>
                                @endif

                                @if($setting->telefono != '')
                                    <li>
                                        <a href="tel:{{ $setting->telefono }}">
                                            <i class="fa-solid fa-phone"></i>
                                            {{ utf8_decode($setting->telefono) }}
                                        </a>
                                    </li>
                                @endif

                                @if($setting->celular != '')
                                    <li>
                                        <a href="tel:{{ $setting->celular }}">
                                            <i class="fa-solid fa-mobile-screen-button"></i>
                                            {{ utf8_decode($setting->celular) }}
                                        </a>
                                    </li>
                                @endif

                                @if($setting->email != '')
                                    <li>
                                        <a href="mailto:{{ utf8_decode($setting->email) }}">
                                            <i class="fa-regular fa-envelope"></i>
                                            {{ utf8_decode($setting->email) }}
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>

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

             <div class="copyright">
                <p>Copyright © <script>document.write(new Date().getFullYear());</script> {{ $setting->nombre }}. All Rights Reserved</p>
            </div>
        </footer>
    @endif

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>

</html>
