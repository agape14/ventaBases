@extends('admin.layouts.app')
@section('content')
<div class="row pt-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white d-flex align-items-center">
                <li class="breadcrumb-item"><a href="{{ URL::previous() }}" class="btn btn-dark btn-sm"><i class="fa fa-chevron-left"></i>  Back</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin#dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active" aria-current="page">Cash on Delivery</li>
            </ol>
          </nav>
    </div>
</div>
    <div class="row">
        <div class="col-4">
            <div class="card shadow-none">
                <div class="card-header">
                    <h4 class="mb-0">Add Locations</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin#storeCos') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="">State Division</label>
                            <select name="stateDivisionId"  id="" class="statediviOption form-control">
                                <option value="">-----Select State Division-----</option>
                                @foreach ($stateDivisions as $item)
                                    <option value="{{ $item->state_division_id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                            @error('stateDivisionId')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">City</label>
                            <select name="cityId" id="" class="cityOption form-control">
                                <option value="">-----Select City-----</option>
                            </select>
                            @error('cityId')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">Township</label>
                            <select name="townshipId" id="" class="townshipOption form-control">
                                <option value="">-----Select Township-----</option>
                            </select>
                            @error('townshipId')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">Status</label>
                            <select name="status" id="" class="form-control">
                                <option value="1">Active</option>
                                <option value="0">Unactive</option>
                            </select>
                            @error('status')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <button class="mt-3 btn btn-primary">Add</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-8">
            @include('admin.cashOnDelivery.list')
        </div>
    </div>
@endsection
@section('script')
<script>
    $('document').ready(function(){
        $('.statediviOption').on('change',function(){
            let id = $(this).val();
            $.ajax({
                url: "{{ route('admin#getCity') }}",
                method: "post",
                dataType: "json",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                },
                beforeSend:function(){
                    $('.cityOption').html('<option>-----Loading-----</option>');
                },
                success:function(response){
                    let cityHtml = "<option>-----select city-----</option>";
                    let townshipHtml = "<option>-----select township-----</option>";
                    for(let i= 0; i < response.cities.length; i++){
                        cityHtml += `<option value="${response.cities[i].city_id}">${response.cities[i].name}</option>`;
                    };
                    $('.cityOption').html(cityHtml);
                    $('.townshipOption').html(townshipHtml);
                }

            })
        })
        $('.cityOption').on('change',function(){
            let id = $(this).val();
            $.ajax({
                url: "{{ route('admin#getTownship') }}",
                method: "post",
                dataType: "json",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                },
                beforeSend:function(){
                    $('.townshipOption').html('<option>-----Loading-----</option>');
                },
                success:function(response){
                    let townshipHtml = "";
                    for(let i= 0; i < response.townships.length; i++){
                        townshipHtml += `<option value="${response.townships[i].township_id}">${response.townships[i].name}</option>`;
                    };
                    $('.townshipOption').html(townshipHtml);
                }

            })
        })
    })
</script>

@endsection
