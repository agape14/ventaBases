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
                          <li class="breadcrumb-item active" aria-current="page">Editar</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    @include('frontEnd.profile/profileSidebar')
                </div>
                <div class="col-9">
                    <div class="mb-4 border-0 rounded card">
                        <div class="bg-white card-header">
                            <h5 class="my-2">Editar Perfil</h5>
                        </div>
                        <div class="card-body">
                            @if (!empty($user->profile_photo_path))
                                <img src="{{ asset('uploads/user/'.$user->profile_photo_path) }}" class="mb-3 rounded-circle" alt="" srcset="" style="width: 100px !important; height: 100px !important">
                            @else
                                <img src="{{ asset('frontEnd/resources/image/user-default.png') }}" class="mb-3 rounded-circle" alt="" srcset="" style="width: 100px !important; height: 100px !important">
                            @endif
                            <form action="{{ route('user#updateProfile') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="" class="form-label">Cambiar foto de perfil</label>
                                    <input name="photo" type="file" class="form-control">
                                    @error('photo')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label">Nombre</label>
                                    <input name="name" type="text" class="form-control" value="{{ $user->name }}">
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label">Correo electrónico</label>
                                    <input name="email" type="email" class="form-control" value="{{ $user->email }}">
                                    @error('email')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <button class="mt-3 text-white shadow btn btn-primary float-end">Actualizar Perfil</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
