{{-- <x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <x-jet-authentication-card-logo />
        </x-slot>

        <x-jet-validation-errors class="mb-4" />

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-jet-label for="email" value="{{ __('Email') }}" />
                <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <div class="mt-4">
                <x-jet-label for="password" value="{{ __('Password') }}" />
                <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-jet-checkbox id="remember_me" name="remember" />
                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-jet-button class="ml-4">
                    {{ __('Log in') }}
                </x-jet-button>
            </div>
        </form>
    </x-jet-authentication-card>
</x-guest-layout> --}}


@extends('frontEnd.layouts.app')
@section('content')
    <section class="py-4">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('frontend#index') }}">Inicio</a></li>
                            {{--<li class="breadcrumb-item"><a href="#">Library</a></li>--}}
                          <li class="breadcrumb-item active" aria-current="page">Iniciar Sesión</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row my-5 py-5"><!-- min-vh-100 -->
                <div class="container d-flex justify-content-center align-items-center ">
                    <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
                        <div class="card border-0  shadow" style="border-radius: 10px">
                            <div class="card-header bg-transparent">
                                <h5 class="mb-0 py-2">Iniciar Sesión</h5>
                            </div>
                            <div class="card-body">
                                @if (session('status'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>{{ session('status') }}</strong>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                                @endif
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="" class="form-label">Correo Electronico</label>
                                        <input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}" required autofocus>
                                        @error('email')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="" class="form-label">Contraseña</label>
                                        <input type="password" class="form-control" name="password" id="password" value="{{ old('password') }}" required autocomplete="current-password">
                                        @error('password')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    @if (Route::has('password.request'))
                                    <a class="text-secondary " href="{{ route('password.request') }}">
                                        {{ __('¿Olvidaste tu contraseña?') }}
                                    </a>
                                @endif
                                <hr>
                                    <div class="mt-3 d-flex align-items-center">
                                        <div class="row">
                                            <div class="col-12">
                                                <button class="btn btn-primary  w-100 text-white  px-3"><span class="fa fa-sign-in"></span> Iniciar Sesión</button>
                                            </div>
                                            <div class="col-12">
                                                <p class="mb-0 ms-2">Si no tienes una cuenta, <a href="{{ route('register') }}" class="text-danger">Regístrese aquí</a> .</p>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


