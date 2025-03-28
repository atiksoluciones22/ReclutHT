@extends('layouts.auth')

@section('form')
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <h1 class="title_auth">Iniciar sesión</h1>

        <div class="input @error('email') input-error @enderror">
            <label for="email">Correo electrónico</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="Correo electrónico" autocomplete="email" autofocus>
            @error('email')<span class="message-error">{{ $message }}</span>@enderror
        </div>

        <div class="input @error('password') input-error @enderror">
            <label for="password">Contraseña</label>
            <input id="password" type="password" name="password" placeholder="Contraseña" autocomplete="current-password">
            @error('password')<span class="message-error">{{ $message }}</span>@enderror
        </div>

        <button type="submit" class="button_auth">
           Iniciar sesión
        </button>
    </form>
@endsection


