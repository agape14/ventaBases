@extends('admin.layouts.app')
@section('content')
<div class="pt-4 row">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white d-flex align-items-center">
                <li class="breadcrumb-item"><a href="{{ URL::previous() }}" class="btn btn-dark btn-sm"><i class="fa fa-chevron-left"></i>  Back</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin#dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active" aria-current="page">Report</li>
            </ol>
          </nav>
    </div>
</div>
<div class="pb-4 row">
    <div class="col-4">
        <div class="rounded card" style="box-shadow: none !important">
            <div class="card-header">
                <h4 class="mb-0">Search by Date</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin#searchByDate') }}" method="GET">
                    <div class="form-group">
                        <label for="">Select Date</label>
                        <input type="date" name="date" class="form-control">
                        @error('date')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <button class="btn btn-primary">Search</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="rounded card" style="box-shadow: none !important">
            <div class="card-header">
                <h4 class="mb-0">Search by Month</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin#searchByMonth') }}">
                    <div class="form-group">
                        <label for="">Select Month</label>
                        <select name="month" id="" class="form-control">
                            <option value="">---Select Month----</option>
                            <option value="January">January</option>
                            <option value="February">February</option>
                            <option value="March">March</option>
                            <option value="April">April</option>
                            <option value="May">May</option>
                            <option value="June">June</option>
                            <option value="July">July</option>
                            <option value="August">August</option>
                            <option value="September">September</option>
                            <option value="October">October</option>
                            <option value="Novenber">Novenber</option>
                            <option value="December">December</option>
                        </select>
                        @error('month')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Select Year</label>
                        <select name="year" id="" class="form-control">
                            <option value="">---Select Year----</option>
                            @for ($i = Carbon\Carbon::now()->format('Y'); $i >= 2019  ; $i--)
                                <option value="{{$i}}">{{ $i }}</option>
                            @endfor
                        </select>
                        @error('year')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <button class="btn btn-primary">Search</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="rounded card" style="box-shadow: none !important">
            <div class="card-header">
                <h4 class="mb-0">Search by Year</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin#searchByYear') }}" method="GET">
                    <div class="form-group">
                        <label for="">Select Year</label>
                        <select name="year" id="" class="form-control">
                            <option value="">---Select Year----</option>
                            @for ($i = Carbon\Carbon::now()->format('Y'); $i >= 2019  ; $i--)
                                <option value="{{$i}}">{{ $i }}</option>
                            @endfor
                        </select>
                        @error('year')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <button class="btn btn-primary">Search</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

