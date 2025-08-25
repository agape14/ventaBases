@extends('admin.layouts.app')

@section('title', 'Acceso Denegado')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title text-danger">
                        <i class="fas fa-exclamation-triangle"></i> Acceso Denegado
                    </h3>
                </div>
                <div class="card-body text-center">
                    <div class="row">
                        <div class="col-md-6 offset-md-3">
                            <div class="error-page">
                                <h2 class="headline text-warning">403</h2>
                                <div class="error-content">
                                    <h3>
                                        <i class="fas fa-exclamation-triangle text-warning"></i> 
                                        ¡Oops! No tienes permisos para acceder a esta sección.
                                    </h3>
                                    <p>
                                        Lo sentimos, pero no tienes los permisos necesarios para acceder al Sistema de Libros.
                                        <br>
                                        <strong>Roles permitidos:</strong> Administrador, Tesorería, Ventas, Libros
                                    </p>
                                    <p>
                                        Si crees que esto es un error, contacta al administrador del sistema.
                                    </p>
                                    <div class="mt-4">
                                        <a href="{{ route('admin#dashboard') }}" class="btn btn-primary">
                                            <i class="fas fa-home"></i> Ir al Dashboard
                                        </a>
                                        <a href="{{ URL::previous() }}" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left"></i> Volver
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.error-page {
    padding: 40px 0;
}
.error-page .headline {
    font-size: 100px;
    font-weight: 300;
    margin-bottom: 20px;
}
.error-content {
    margin-top: 20px;
}
.error-content h3 {
    margin-bottom: 20px;
}
</style>
@endsection
