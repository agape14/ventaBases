@extends('admin.layouts.app')
@section('content')
<div class="row pt-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white d-flex align-items-center">
                <li class="breadcrumb-item"><a href="{{ URL::previous() }}" class="btn btn-dark btn-sm"><i class="fa fa-chevron-left"></i>  Back</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin#dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{ route('admin#userList') }}">User Lists</a></li>
              <li class="breadcrumb-item active" aria-current="page">Crear Usuario</li>
            </ol>
          </nav>
    </div>
</div>
<div class="row">
    <div class="col-6">
        <div class="card shadow-none">
            <div class="card-header">
                <h4 class="mb-0">Crear Nuevo Usuario</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin#storeUser') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name">Nombre</label>
                        <input name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Ingrese el nombre completo">
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Ingrese el email">
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input name="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Ingrese la contraseña">
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Confirmar Contraseña</label>
                        <input name="password_confirmation" type="password" class="form-control" placeholder="Confirme la contraseña">
                    </div>
                    <div class="form-group">
                        <label for="role">Rol</label>
                        <select name="role" class="form-control @error('role') is-invalid @enderror">
                            <option value="">Seleccione un rol</option>
                            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Usuario</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrador</option>
                            <option value="tesoreria" {{ old('role') == 'tesoreria' ? 'selected' : '' }}>Tesoreria</option>
                            <option value="ventas" {{ old('role') == 'ventas' ? 'selected' : '' }}>Ventas</option>
                            <option value="libros" {{ old('role') == 'libros' ? 'selected' : '' }}>Libros</option>
                        </select>
                        @error('role')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <button type="submit" class="mt-3 btn btn-primary">
                        <i class="fas fa-save"></i> Crear Usuario
                    </button>
                    <a href="{{ route('admin#userList') }}" class="mt-3 btn btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </form>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card shadow-none">
            <div class="card-header">
                <h5 class="mb-0">Información de Roles</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h6><i class="fas fa-info-circle"></i> Descripción de Roles:</h6>
                    <ul class="mb-0">
                        <li><strong>Usuario:</strong> Acceso básico al sistema</li>
                        <li><strong>Administrador:</strong> Acceso completo a todas las funciones</li>
                        <li><strong>Tesoreria:</strong> Acceso a gestión de pedidos y sistema de libros</li>
                        <li><strong>Ventas:</strong> Acceso al sistema de libros</li>
                        <li><strong>Libros:</strong> Acceso específico al sistema de libros</li>
                    </ul>
                </div>
                <div class="alert alert-warning">
                    <h6><i class="fas fa-exclamation-triangle"></i> Notas importantes:</h6>
                    <ul class="mb-0">
                        <li>La contraseña debe tener al menos 8 caracteres</li>
                        <li>El email debe ser único en el sistema</li>
                        <li>Los usuarios pueden cambiar su contraseña después del primer acceso</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
