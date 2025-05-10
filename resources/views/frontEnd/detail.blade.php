@extends('frontEnd.layouts.app')
@section('content')
<!-- -------------------------------product details------------------------------------  -->
    <section class="pt-4">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb d-flex align-items-center ">
                            <li class="breadcrumb-item"><a href="{{ URL::previous() }}" class="btn btn-dark btn-sm"><i class="fa fa-chevron-left"></i> Regresar</a></li>
                          <li class="breadcrumb-item"><a href="{{ route('frontend#index') }}">Inicio</a></li>
                          <li class="breadcrumb-item"><a href="#">Productos</a></li>
                          <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="bg-white row">
                        <div class="col-sm-12 col-md-4 col-lg-4">
                            <div class="border-0 rounded card">
                                <div class="px-4 card-body">
                                    <div class="mb-4 overflow-hidden big-img">
                                        {{-- preview image --}}
                                        <img src="{{ asset('uploads/products/'.$product->preview_image) }}" class="img-fluid" alt="" srcset="">
                                    </div>
                                    @if (!count($multiImages) == 0)
                                    <div class="m-auto row small-img-slider position-relative owl-carousel owl-theme d-none d-sm-block">
                                        @foreach ($multiImages as $item)
                                            <div class="item">
                                                <div class="mx-1 overflow-hideen small-img">
                                                    <img src="{{ asset('uploads/products/'.$item->image)}}" class="" alt="" srcset="">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-8 col-lg-8">
                            <div class="border-0 rounded card">
                                <div class="card-body">
                                    <h5>{{ $product->name }}</h5>
                                    <hr>
                                    {!! $product->html_details !!}
                                    @if ($colors->count() > 0)
                                        @foreach ($colors as $color)
                                            @if ($color->available_stock > 0)
                                                <p>Disponibilidad : <span class="text-success">En stock</span></p>
                                            @else
                                                <p>Disponibilidad : <span class="text-danger">Sin stock</span></p>
                                            @endif
                                        @endforeach
                                    @endif
                                    <p class="text-black-50">{{ $product->short_description }}</p>
                                    <hr>
                                    @if (!empty($product->discount_price))
                                        @php
                                            $amount = $product->discount_price / $product->selling_price;
                                            $percentage = round($amount*100);
                                        @endphp
                                        <p class="">Descuento : <span class=""> {{$percentage}} %</span></p>
                                        <hr>
                                    @endif

                                    <div class="d-flex align-items-center">
                                        <p class="mb-0 me-3">Cantidad : </p>
                                        <div class="p-1 rounded d-flex" style="width: 150px ;">
                                            <input type="number" class="quantity form-control" placeholder="qty" value="1" max="10" min="1">
                                        </div>
                                    </div>
                                    <small class="text-danger qtyErrorMessage d-none">La cantidad debe ser mínima: 1 o máxima: 10</small>
                                    <hr>
                                    <div class="d-flex align-items-baseline">
                                        @php
                                            $price = !empty($product->discount_price) ? $product->selling_price - $product->discount_price : $product->selling_price;
                                        @endphp
                                            <h5 class="mb-0 text-danger">SubTotal : S/ {{number_format($price, 2)}}</h5>
                                        @if (!empty($product->discount_price))
                                            <p class="mb-0 h6 ms-2 text-black-50 text-decoration-line-through">S/ {{ number_format($product->selling_price, 2)  }}</p>
                                        @endif
                                    </div>
                                    <hr>
                                    <div class="mt-4">
                                        @if (Session::has('cart'))
                                            <button class="text-white addToCart btn btn-primary" onclick="addToCart({{$product->product_id}},{{ $price }})">Agregar al carrito <i class="fa fa-cart-plus" aria-hidden="true"></i></button>
                                            <a class="text-white btn btn-success" href="{{ route('frontend#viewCarts') }}">Ver mi carrito <i class="fa fa-cart-arrow-down" aria-hidden="true"></i></a>
                                            <a class="text-white btn btn-secondary" href="{{ route('frontend#index') }}">Seguir comprando <i class="fa fa-plus" aria-hidden="true"></i></a>
                                        @else
                                            <button class="text-white addToCart btn btn-primary" onclick="addToCart({{$product->product_id}},{{ $price }})">Agregar al carrito <i class="fa fa-cart-plus" aria-hidden="true"></i></button>
                                            <a id="viewCartButton" class="text-white btn btn-success d-none" href="{{ route('frontend#viewCarts') }}"> Ver mi carrito <i class="fa fa-cart-arrow-down" aria-hidden="true"></i></a>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="my-4 border-0 card">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-sm-12 col-md-4 col-lg-4">
                                  <div class="list-group" id="list-tab" role="tablist">
                                    <a class="list-group-item list-group-item-action active" id="list-home-list" data-bs-toggle="list" href="#list-home" role="tab" aria-controls="list-home">Detalles del producto</a>
                                    <a class="list-group-item list-group-item-action" id="list-profile-list" data-bs-toggle="list" href="#list-profile" role="tab" aria-controls="list-profile">Revisión del producto</a>
                                  </div>
                                </div>
                                <div class="col-sm-12 col-md-8 col-lg-8">
                                  <div class="tab-content" id="nav-tabContent">
                                    <div class="tab-pane fade show active" id="list-home" role="tabpanel" aria-labelledby="list-home-list">
                                        <h5>Detalles del Producto</h5>
                                        <hr>
                                        <p class="text-black-50">{{ $product->long_description }}</p>
                                    </div>
                                    <div class="tab-pane fade" id="list-profile" role="tabpanel" aria-labelledby="list-profile-list">
                                        @foreach ($product->productReview as $review)
                                        @if ($review->status == 1)
                                            <div class="mb-3 border-0 rounded bg-light card">
                                                <div class="card-body">
                                                    <div class="">
                                                        <div class="mb-3 d-flex">
                                                            @if (!empty($review->user->profile_photo_path))
                                                                <img src="{{ asset('uploads/user/'.$review->user->profile_photo_path) }}" class="p-1 bg-white rounded-circle" alt="" srcset="" style="width: 50px !important; height: 50px !important">
                                                            @else
                                                                <img src="{{ asset('frontEnd/resources/image/user-default.png') }}" class="p-1 bg-white rounded-circle" alt="" srcset="" style="width: 50px !important; height: 50px !important">
                                                            @endif
                                                                <div class="ms-2">
                                                                    <p class="mb-0">{{ $review->user->name }}</p>
                                                                    <p class="mb-0 text-secondary">{{ $review->created_at->diffForHumans() }}</p>
                                                                </div>
                                                        </div>
                                                        <p class="mb-1">{{ $review->title }}</p>
                                                        <p class="text-secondary">"{{ $review->comment }}"</p>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            @endforeach
                                        <div class="bg-white border-0 card">
                                            <div class="card-body">
                                                <h5 class="">Escribe tu propia reseña</h5>
                                                <hr>
                                                <form action="{{ route('user#storeReview',$product->product_id) }}" method="POST">
                                                    @csrf
                                                    <div class="mb-3">
                                                        <label for="" class="form-label">Titulo</label>
                                                        <input name="title" type="text" class="form-control">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="" class="form-label">Tu reseña</label>
                                                        <textarea name="comment" id="" class="form-control" cols="" rows="3"></textarea>
                                                    </div>
                                                    <button class="text-white float-end btn btn-primary">Enviar reseña</button>
                                                </form>
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

        </div>
    </section>
@endsection
@section('script')
<script>
    $('document').ready(function(){
        //get size
        $('.colorOption').on('click',function(){
            let productId = "{{ $product->product_id }}";
            let colorId = $(this).val();
            $.ajax({
                url: "{{ route('frontend#getProductSize') }}",
                method: "post",
                dataType: "json",
                data: {
                    _token: '{{ csrf_token() }}',
                    colorId: colorId,
                    productId: productId,
                },
                beforeSend:function(){
                    let sizeOptionHtml = '<option value="">----loading----</option>';
                    $('.sizeOption').html(sizeOptionHtml);
                },
                success:function(response){
                    if(response.sizes.length != 0){
                        let sizeOptionHtml = '';
                        for(let i =0 ; i < response.sizes.length ; i++){
                            sizeOptionHtml += `
                                <option value="${response.sizes[i].size_id}">${response.sizes[i].sizeName}</option>
                            `;
                        }
                        $('.sizeOption').html(sizeOptionHtml);
                    }
                }

            })
        })
    })
</script>
@endsection
