@extends('layouts.app')

<div class="row justify-content-center">
    <div class="col-md-12 d-flex">
        <div class="col-md-6 color-background">
            <div class="d-flex justify-content-center align-items-center" style="height: 100%;">
                <img class="logo2" src="{{ asset('images/logoMarket.png') }}" alt="Market Check">
            </div>
        </div>

        <div class="col-md-6">
        <div class="card inicio">
        <div class="card-header text-center negrita color-background2">{{ __('INICIA SESION') }}</div>

<div class="card-body ">
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="row mb-6 mb-4">
            <label for="email" class="col-md-4 col-form-label text-md-end negrita">{{ __('Correo Electronico') }}</label>

            <div class="col-md-6">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <label for="password" class="col-md-4 col-form-label text-md-end negrita">{{ __('Contraseña') }}</label>

            <div class="col-md-6">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6 offset-md-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                    <label class="form-check-label negrita" for="remember">
                        {{ __('Recuerdame') }}
                    </label>
                </div>
            </div>
        </div>

        <div class="row mb-0 text-center">
    <div class="col-md-12">
        <button type="submit" class="btn btn-primary">
            {{ __('Ingresar') }}
        </button>
    </div>
</div>

@if (Route::has('password.request'))
    <div class="row mb-3 text-center">
        <div class="col-md-12">
            <a class="btn btn-link" href="{{ route('password.request') }}">
                {{ __('¿Olvidaste tu contraseña?') }}
            </a>
        </div>
    </div>
@endif

            </div>
        </div>
</form>
        </div>
    </div>
    
</div>






