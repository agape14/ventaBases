@extends('frontEnd.layouts.app')
@section('content')
    <section class="py-4">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb d-flex align-items-center ">
                            <li class="breadcrumb-item"><a href="{{ URL::previous() }}" class="btn btn-dark btn-sm"><i class="fa fa-chevron-left"></i> Regresar</a></li>
                          <li class="breadcrumb-item"><a href="{{ route('frontend#index') }}">Inicio</a></li>
                          <li class="breadcrumb-item active" aria-current="page">My Wishlists</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row min-vh-100">
                <div class="col-12">
                    <div class="card border-0">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">My Wishlists</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="bg-primary text-nowrap text-white">
                                        <tr class="text-center">
                                            <th>#</th>
                                            <th style="width: 100px">Product</th>
                                            <th style="">Name</th>
                                            <th>Description</th>
                                            <th style="">Price</th>
                                            <th>Action</th>
                                            <th style="">Remove</th>
                                        </tr>
                                    </thead>
                                    <tbody class="wishListTable">
                                        <tr class="text-center text-danger">
                                            <td colspan="7" class=" py-3">
                                                There is No WishLists
                                            </td>
                                        </tr>

                                    </tbody>

                                </table>
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

    function showAllWishlist(){
        $.ajax({
                url: "{{ route('user#getWishlist') }}",
                method: "get",
                dataType: "json",
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success:function(response){

                    let wishlistHtml = '';
                    if(response.wishlists.length != 0){
                        for(let i=0; i < response.wishlists.length; i++){
                            wishlistHtml += `
                            <tr class="wishlistRow${response.wishlists[i].wish_list_id} text-center">
                                <td>${i+1}</td>
                                <td>
                                    <img src="{{ asset('uploads/products/') }}/${response.wishlists[i].preview_image}" alt="" srcset="" style="width:100px;height:100px">
                                </td>
                                <td class="text-start">${ response.wishlists[i].name }</td>
                                <td class="text-start">${response.wishlists[i].short_description}</td>
                                <td>${ response.wishlists[i].discount_price == null || response.wishlists[i].discount_price == 0 ? response.wishlists[i].selling_price : response.wishlists[i].selling_price - response.wishlists[i].discount_price }</td>
                                <td>
                                    <a href="{{ url('product/detail/') }}/${response.wishlists[i].product_id}" class="btn btn-primary btn-sm text-white"><i class="fas fa-eye me-2"></i>View Detail</a>
                                </td>
                                <td>
                                    <button onclick="deleteWishlist(${response.wishlists[i].wish_list_id})" class="btn btn-danger btn-sm text-white"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            `;
                        }

                        $('.wishListTable').html(wishlistHtml);
                    }
                }
            })
    }
    function deleteWishlist(id){
        Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('user/wishlist/delete/') }}/"+id,
                    method: "get",
                    dataType: "json",
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success:function(response){
                        $('.wishlistRow'+id).remove();
                        Toast.fire({
                                icon: 'success',
                                title: response.success,
                            })

                    }
                })
            }
        })

    }
    $('document').ready(function(){
        showAllWishlist();
    })
</script>
@endsection
