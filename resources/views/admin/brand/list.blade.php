<div class="card rounded" style="box-shadow: none !important">
    <div class="card-header">
        <h4>All Brand</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="dataTable">
                <thead class="bg-primary">
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Image</th>
                    <th>Created At</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <th>{{ $item->brand_id }}</th>
                            <td>{{ $item->name }}</td>
                            <td>
                                <img src="{{ asset('uploads/brands/'.$item->image) }}" class="rounded" alt="" srcset="" style="width: 60px; height: 60px;">
                            </td>
                            <td>{{ $item->created_at }}</td>
                            <td>
                                <a href="{{ route('admin#editBrand',$item->brand_id) }}" class="btn btn-success btn-sm"><i class="fas fa-edit"></i></a>
                                <a href="{{ route('admin#deleteBrand',$item->brand_id) }}" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure to delete?')"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
              </table>
        </div>
    </div>
</div>
