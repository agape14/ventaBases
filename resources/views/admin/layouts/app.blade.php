<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @php
      $companyInfo = App\Models\CompanySetting::orderBy('id','desc')->first();
  @endphp
  <!-- App favicon -->
  <link rel="shortcut icon" href="{{ asset('uploads/logo/'.$companyInfo->logo) }}">

  <title>{{$companyInfo->company_name}}</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{ asset('admin/plugins/fontawesome-free/css/all.min.css') }}">
  {{-- toaster  --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  <!-- data tables -->
  <link rel="stylesheet" href="{{ asset('admin/plugins/data_table/dataTables.bootstrap4.min.css') }}">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.css" integrity="sha512-Woz+DqWYJ51bpVk5Fv0yES/edIMXjj3Ynda+KWTIkGoynAMHrqTcDUQltbipuiaD5ymEo9520lyoVOo9jCQOCA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('admin/dist/css/adminlte.css') }}">
  <style>
    .sidebar-light-white {
      background-color: #02aab0 !important ;
    }
    .sidebar-light-white .nav-link {
      color: #fff !important;
    }
    .sidebar-light-white .nav-link.active{
      color: #02aab0 !important;
    }

    .nav-treeview .nav-link.active{
        color:#fff !important;
        background-color: #02aab0 !important;
        border: 1px solid #fff !important;
    }
  </style>
  @yield('style')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="ml-auto navbar-nav">
      <!-- Navbar Search -->
      <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="fas fa-search"></i>
        </a>
        <div class="navbar-search-block">
          <form class="form-inline">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </li>

      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-dark navbar-badge font-weight-bolder">{{auth()->user()->unreadNotifications->count()}}</span>
        </a>
        <div class="bg-white dropdown-menu dropdown-menu-lg dropdown-menu-right" style="min-width: 400px !important">
          <span class="text-left dropdown-header">{{auth()->user()->unreadNotifications->count()}} Notificaciones</span>
          @if (auth()->user()->unreadNotifications->count() == 0)
            <div class="dropdown-divider"></div>
            <a href="#" class="py-3 text-center dropdown-item">
                <span class="mb-0 text-muted">No hay notificaciones</span>
            </a>
          @else
            @foreach (auth()->user()->unreadNotifications as $noti)
                <div class="dropdown-divider"></div>
                <a href="{{ route('admin#showOrder',['id'=> $noti->data['orderId'],'notiId'=> $noti->id]) }}" class="dropdown-item">
                    <span class="mb-0 text-muted text-wrap"><span class="text-danger">"{{ $noti->data['userName'] }}"</span> {{$noti->data['message']}} {{$noti->created_at->diffForHumans()}}</span>
                </a>
            @endforeach
          @endif




        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-light-white elevation-4">
    <!-- Brand Logo -->
    <a href="{{route('frontend#index')}}" class="brand-link">
      <img src="{{ asset('uploads/logo/'.$companyInfo->logo) }}" alt="AdminLTE Logo" class="bg-white brand-image img-circle elevation-1" style="opacity: 1;">
      <span class="text-white brand-text font-weight-bold">{{$companyInfo->company_name}}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
            @if (auth()->user()->role == 'admin' || auth()->user()->role == 'tesoreria')
          <li class="nav-item">
            <a href="{{ route('admin#dashboard') }}" class="nav-link {{ Request::url() == route('admin#dashboard') ? 'active' : '' }}">
                <i class="nav-icon fas fa-home"></i>
                <p>
                    Panel de Control
                </p>
            </a>
          </li>
          @endif
          @if (auth()->user()->role == 'admin')
          <li class="text-white nav-header text-uppercase">Tools</li>
          <li class="nav-item">
            <a href="{{ route('admin#brand') }}" class="nav-link {{ Request::url() == route('admin#brand') ? 'active' : '' }}">
                <i class="nav-icon fas fa-cube"></i>
              <p>
                Brand
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('admin#color') }}" class="nav-link {{ Request::url() == route('admin#color') ? 'active' : '' }}">
                <i class="nav-icon fas fa-palette"></i>
              <p>
                Color
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('admin#size') }}" class="nav-link {{ Request::url() == route('admin#size') ? 'active' : '' }}">
                <i class="fas fa-shapes nav-icon"></i>
              <p>
                Size
              </p>
            </a>
          </li>
          <li class="text-white nav-header text-uppercase">Manage Category</li>
          <li class="nav-item">
            <a href="{{ route('admin#category') }}" class="nav-link {{ Request::url() == route('admin#category') ? 'active' : '' }}">
                <i class="fas fa-layer-group nav-icon"></i>
              <p>
                Category
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('admin#subCategory') }}" class="nav-link {{ Request::url() == route('admin#subCategory') ? 'active' : '' }}">
                <i class="fas fa-layer-group nav-icon"></i>
              <p>
                SubCategory
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('admin#subSubCat') }}" class="nav-link {{ Request::url() == route('admin#subSubCat') ? 'active' : '' }}">
                <i class="fas fa-layer-group nav-icon"></i>
              <p>
                SubSubCategory
              </p>
            </a>
          </li>
          <li class="text-white nav-header text-uppercase">Manage Products</li>
          <li class="nav-item">
            <a href="{{ route('admin#product') }}" class="nav-link {{ Request::url() == route('admin#product') ? 'active' : '' }}">
                <i class="nav-icon fab fa-product-hunt"></i>
              <p>
                Products
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('admin#coupon') }}" class="nav-link {{ Request::url() == route('admin#coupon') ? 'active' : '' }}">
                <i class="nav-icon fas fa-percent"></i>
              <p>
                Coupons
              </p>
            </a>
          </li>
          <li class="text-white nav-header text-uppercase">Manage Stocks</li>
          <li class="nav-item">
            <a href="{{ route('admin#productStock') }}" class="nav-link {{ Request::url() == route('admin#productStock') ? 'active' : '' }}">
                <i class="nav-icon fab fa-product-hunt"></i>
              <p>
                Stocks
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('admin#stockHistory') }}" class="nav-link {{ Request::url() == route('admin#stockHistory') ? 'active' : '' }}">
                <i class="nav-icon fab fa-product-hunt"></i>
              <p>
                Stock History
              </p>
            </a>
          </li>

          <li class="text-white nav-header text-uppercase">Manage Delivery Locations</li>
          <li class="nav-item">
            <a href="{{ route('admin#statedivision') }}" class="nav-link {{ Request::url() == route('admin#statedivision') ? 'active' : '' }}">
                <i class="nav-icon fas fa-map-marker-alt"></i>
              <p>
                State Divisions
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('admin#city') }}" class="nav-link {{ Request::url() == route('admin#city') ? 'active' : '' }}">
                <i class="nav-icon fas fa-map-marker-alt"></i>
              <p>
                City
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('admin#township') }}" class="nav-link {{ Request::url() == route('admin#township') ? 'active' : '' }}">
                <i class="nav-icon fas fa-map-marker-alt"></i>
              <p>
                Township
              </p>
            </a>
          </li>
          <li class="text-white nav-header text-uppercase">Manage COS Locations</li>
          <li class="nav-item">
            <a href="{{ route('admin#cos') }}" class="nav-link {{ Request::url() == route('admin#cos') ? 'active' : '' }}">
                <i class="nav-icon fas fa-map-marker-alt"></i>
              <p>
                Cash on Delivery
              </p>
            </a>
          </li>
          @endif
          @if (auth()->user()->role == 'admin' || auth()->user()->role == 'tesoreria')
          <li class="text-white nav-header text-uppercase">Gestionar Pedidos</li>
          <li class="nav-item">
            <a href="{{ route('admin#order') }}" class="nav-link {{ Request::url() == route('admin#order') ? 'active' : '' }}">
                <i class="nav-icon fas fa-shopping-bag"></i>
              <p>
                Pedidos
              </p>
            </a>
        </li>
        @if (auth()->user()->role == 'admin')
        <li class="nav-item">
            <a href="{{ route('admin#pendingOrder') }}" class="nav-link {{ Request::url() == route('admin#pendingOrder') ? 'active' : '' }}">
                <i class="nav-icon fas fa-shopping-bag"></i>
              <p>
                Pedidos Pendientes
              </p>
            </a>
        </li>
        @endif
        
        @if (in_array(auth()->user()->role, ['admin', 'tesoreria', 'ventas', 'libros']))
        <!-- Sistema de Libros -->
        <li class="text-white nav-header text-uppercase">Sistema de Libros</li>
        <li class="nav-item">
            <a href="{{ route('admin#libros.index') }}" class="nav-link {{ Request::url() == route('admin#libros.index') ? 'active' : '' }}">
                <i class="nav-icon fas fa-book"></i>
              <p>
                Ventas de Libros
              </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin#libros.create') }}" class="nav-link {{ Request::url() == route('admin#libros.create') ? 'active' : '' }}">
                <i class="nav-icon fas fa-plus"></i>
              <p>
                Nueva Venta
              </p>
            </a>
        </li>
        @endif
        @endif
        @if (auth()->user()->role == 'admin')
        <li class="text-white nav-header text-uppercase">Report Orders</li>
        <li class="nav-item">
            <a href="{{ route('admin#report') }}" class="nav-link {{ Request::url() == route('admin#report') ? 'active' : '' }}">
                <i class="nav-icon fas fa-shopping-bag"></i>
              <p>
                Report Orders
              </p>
            </a>
        </li>
        <li class="text-white nav-header text-uppercase">Manage Product Review</li>
        <li class="nav-item">
        <a href="{{ route('admin#productReview') }}" class="nav-link {{ Request::url() == route('admin#productReview') ? 'active' : '' }}">
            <i class="nav-icon fas fa-comment-alt"></i>

            <p>
              All Product Review
            </p>
          </a>
      </li>
      <li class="nav-item">
        <a href="{{ route('admin#pendingReview') }}" class="nav-link {{ Request::url() == route('admin#pendingReview') ? 'active' : '' }}">
            <i class="nav-icon fas fa-comment-alt"></i>
            <p>
                Pending Review
            </p>
          </a>
      </li>
      <li class="text-white nav-header text-uppercase">Manage Payment</li>
        <li class="nav-item">
        <a href="{{ route('admin#paymentInfo') }}" class="nav-link {{ Request::url() == route('admin#paymentInfo') ? 'active' : '' }}">
            <i class="nav-icon fas fa-credit-card"></i>

            <p>
              Payment Info
            </p>
          </a>
      </li>
      <li class="nav-item">
        <a href="{{ route('admin#paymentTransition') }}" class="nav-link {{ Request::url() == route('admin#paymentTransition') ? 'active' : '' }}">
            <i class="nav-icon fas fa-credit-card"></i>

            <p>
              Payment Transitions
            </p>
          </a>
      </li>
        <li class="text-white nav-header text-uppercase">Manage User</li>
          <li class="nav-item">
            <a href="{{ route('admin#userList') }}" class="nav-link {{ Request::url() == route('admin#userList') ? 'active' : '' }}">
              <i class="nav-icon fas fa-users"></i>
              <p>
                User Lists
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('admin#adminList') }}" class="nav-link {{ Request::url() == route('admin#adminList') ? 'active' : '' }}">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Admin Lists
              </p>
            </a>
          </li>
          <li class="text-white nav-header text-uppercase">Manage Company Info</li>
          <li class="nav-item">
            <a href="{{ route('admin#companySetting') }}" class="nav-link {{ Request::url() == route('admin#companySetting') ? 'active' : '' }}">
              <i class="nav-icon fas fa-cog"></i>
              <p>
                Company Info
              </p>
            </a>
          </li>
          @endif
          @if (auth()->user()->role == 'admin' || auth()->user()->role == 'tesoreria')
          <li class="text-white nav-header text-uppercase">Gestionar Perfil</li>
          <li class="nav-item">
            <a href="{{ route('admin#editProfile') }}" class="nav-link {{ Request::url() == route('admin#editProfile') ? 'active' : '' }}">
              <i class="nav-icon fas fa-user-circle"></i>
              <p>
                Editar Perfil
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('admin#editPassword') }}" class="nav-link {{ Request::url() == route('admin#editPassword') ? 'active' : '' }}">
              <i class="nav-icon fas fa-lock"></i>
              <p>
                Cambiar Contraseña
              </p>
            </a>
          </li>
          @endif
          
          <!-- Botón de Cerrar Sesión para todos los roles -->
          <li class="nav-item">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button onclick="return confirm('¿Está seguro de que desea cerrar sesión?')" class="btn nav-link d-flex align-items-center justify-content-start">
                    <i class="fas fa-sign-out-alt nav-icon"></i>
                    <p class="">Cerrar Sesión</p>
                </button>
            </form>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">

        @yield('content')
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
    <div class="p-3">
      <h5>Title</h5>
      <p>Sidebar content</p>
    </div>
  </aside>
  <!-- /.control-sidebar -->


</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="{{ asset('admin/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('admin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
{{-- datatable --}}
<script src="{{ asset('admin/plugins/data_table/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('admin/plugins/data_table/dataTables.bootstrap4.min.js')}}"></script>
{{-- sweet alert  --}}
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js" integrity="sha512-k2GFCTbp9rQU412BStrcD/rlwv1PYec9SNrkbQlo6RZCf75l6KcC3UwDY8H5n5hl4v77IDtIPwOk9Dqjs/mMBQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- AdminLTE App -->
<script src="{{ asset('admin/dist/js/adminlte.min.js') }}"></script>
@yield('script')
<script>
    $(document).ready(function () {
         $('#dataTable').DataTable({
            order: [[1, 'desc']],
            language: {
                url: "{{ asset('vendor/datatables/lang/spanish.json') }}" // Ruta al archivo de idioma
            }
        } );

        $('#tblPedidos').DataTable({
            order: [[0, 'desc']],
            language: {
                url: "{{ asset('vendor/datatables/lang/spanish.json') }}" // Ruta al archivo de idioma
            }
        } );
    });

    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 4000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    });
    @if (Session::has('success'))
        Toast.fire({
                    icon: 'success',
                    title: "{{ Session::get('success') }}",
                })
    @endif

    @if (Session::has('registrosigiconfirmado'))
        Swal.fire({
                    icon: 'success',
                    title: 'Comprobante Electronico Registrado',
                    html: "{!! Session::get('registrosigiconfirmado') !!}",
                    confirmButtonText: 'Aceptar'
                })
    @endif

    @if (Session::has('error'))
    Swal.fire({
                icon: 'error',
                text: '{{ Session::get('error') }}',
            })

    @endif
</script>

</body>
</html>
