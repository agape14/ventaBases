<div class="bg-white border-0 rounded card">
    <div class="card-body">
        <div class="mb-3">Bienvenido <h5 class="mb-0 d-inline-block">{{ auth()->user()->name }}</h5></div>
        <div class="list-group">
            <a href="{{ route('user#profile') }}" class="list-group-item list-group-item-action {{ Request::url() == route('user#profile') ? 'active' : '' }}" aria-current="true">
              Editar Perfil
            </a>
            <a href="{{ route('user#editPassword') }}" class="list-group-item list-group-item-action {{ Request::url() == route('user#editPassword') ? 'active' : '' }}">Cambiar Contraseña</a>
            {{--<a href="{{ route('user#myReview') }}" class="list-group-item list-group-item-action {{ Request::url() == route('user#myReview') ? 'active' : '' }} ">Mis reseñas de productos</a>--}}
            <a href="{{ route('user#myOrder') }}" class="list-group-item list-group-item-action {{ Request::url() == route('user#myOrder') ? 'active' : '' }} ">Mis pedidos</a>
            {{--<a href="{{ route('user#misPagos') }}" class="list-group-item list-group-item-action {{ Request::url() == route('user#misPagos') ? 'active' : '' }} ">Pagar  y Confirmar</a>
            <a href="#" class="list-group-item list-group-item-action ">Orden de devolución</a>
            <a href="#" class="list-group-item list-group-item-action ">Cancelar pedido</a>
            <a href="#" class="list-group-item list-group-item-action ">Cerrar sesión</a>--}}

        </div>
    </div>
</div>
