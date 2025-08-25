@extends('admin.layouts.app')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3 mb-0">Lista de Usuarios</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-white d-flex align-items-center mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin#dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Usuarios</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Gestión de Usuarios</h3>
                    <div class="d-flex align-items-center">
                        <select id="filtro-rol" class="form-control mr-2" style="width: auto;">
                            <option value="">Todos los roles</option>
                            <option value="user">Usuario</option>
                            <option value="admin">Administrador</option>
                            <option value="tesoreria">Tesoreria</option>
                            <option value="ventas">Ventas</option>
                            <option value="libros">Libros</option>
                        </select>
                        <a href="{{ route('admin#createUser') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Crear Usuario
                        </a>
                    </div>
                </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="dataTable">
                        <thead class="bg-primary">
                            <tr>
                                <th>#</th>
                                <th>Photo</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Crated At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <th>{{ $item->id }}</th>
                                    <th>
                                        @if (!empty($item->profile_photo_path))
                                            <img src="{{ asset('/uploads/user/'.$item->profile_photo_path) }}" class="rounded shadow"  alt="" srcset="" style="width: 80px; height: 80px;">
                                        @else
                                            <img src="{{ asset('frontEnd/resources/image/user-default.png') }}" class="bg-white rounded shadow"  alt="" srcset="" style="width: 80px; height: 80px;">
                                        @endif
                                    </th>
                                    <th>{{ $item->name }}</th>
                                    <th>{{ $item->email }}</th>
                                    <th>
                                     @switch($item->role)
                                         @case('admin')
                                             <span class="badge badge-danger">Administrador</span>
                                             @break
                                         @case('tesoreria')
                                             <span class="badge badge-warning">Tesoreria</span>
                                             @break
                                         @case('ventas')
                                             <span class="badge badge-info">Ventas</span>
                                             @break
                                         @case('libros')
                                             <span class="badge badge-success">Libros</span>
                                             @break
                                         @default
                                             <span class="badge badge-secondary">Usuario</span>
                                     @endswitch
                                 </th>
                                    <th>{{ $item->created_at }}</th>
                                    <td>
                                        <a href="{{ route('admin#editUser',$item->id) }}" class="btn btn-success btn-sm"><i class="fas fa-edit"></i></a>
                                        <a href="{{ route('admin#deleteUser',$item->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure to delete?')"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                        </table>
                                 </div>
             </div>
         </div>
     </div>
 </div>

@section('script')
<script>
$(document).ready(function() {
    // Esperar un momento para asegurar que DataTable esté inicializado
    setTimeout(function() {
        // Obtener la instancia de DataTable
        var table = $('#dataTable').DataTable();
        
        // Filtro por rol
        $('#filtro-rol').on('change', function() {
            var rol = $(this).val();
            
            if (rol === '') {
                // Mostrar todos los usuarios
                table.column(4).search('').draw();
            } else {
                // Filtrar por rol específico
                var rolText = '';
                switch(rol) {
                    case 'admin':
                        rolText = 'Administrador';
                        break;
                    case 'tesoreria':
                        rolText = 'Tesoreria';
                        break;
                    case 'ventas':
                        rolText = 'Ventas';
                        break;
                    case 'libros':
                        rolText = 'Libros';
                        break;
                    case 'user':
                        rolText = 'Usuario';
                        break;
                }
                table.column(4).search(rolText).draw();
            }
        });
    }, 100);
});
</script>
@endsection
