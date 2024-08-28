@extends('frontEnd.layouts.app')
@section('content')
    <section class="py-4 min-vh-100">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb d-flex align-items-center ">
                            <li class="breadcrumb-item"><a href="{{ URL::previous() }}" class="btn btn-dark btn-sm"><i class="fa fa-chevron-left"></i> Regresar</a></li>
                          <li class="breadcrumb-item"><a href="{{ route('frontend#index') }}">Inicio</a></li>
                          <li class="breadcrumb-item"><a href="#">Perfil</a></li>
                          <li class="breadcrumb-item active" aria-current="page">Cambiar Contraseña</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    @include('frontEnd.profile/profileSidebar')
                </div>
                <div class="col-9">
                    <div class="border-0 rounded card">
                        <div class="bg-white card-header">
                            <h5 class="my-2">Cambiar Contraseña</h5>
                        </div>
                        <div class="card-body">

                            <form action="{{ route('admin#updatePassword') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label for="" class="form-label">Contraseña actual</label>
                                    <input name="oldPassword" type="password" class="form-control" value="">
                                    @error('oldPassword')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label">Nueva Contraseña</label>
                                    <input name="newPassword" type="password" class="form-control" value="">
                                    @error('newPassword')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label">Confirmar Contraseña</label>
                                    <input name="confirmPassword" type="password" class="form-control" value="">
                                    @error('confirmPassword')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <button class="mt-3 text-white shadow btn btn-primary float-end">Actualizar Contraseña</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
