<div class="card rounded" style="box-shadow: none !important">
    <div class="card-header">
        <h4>All Cities</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="dataTable">
                <thead class="bg-primary">
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>City</th>
                    <th>State Division</th>
                    <th>Created At</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td>{{ $item->township_id }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->cityName }}</td>
                            <td>{{ $item->stateDivisionName }}</td>
                            <td>{{ $item->created_at }}</td>
                            <td>
                                <a href="{{ route('admin#editTownship',$item->township_id ) }}" class="btn btn-success btn-sm"><i class="fas fa-edit"></i></a>
                                <a href="{{ route('admin#deleteTownship',$item->township_id ) }}" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure to delete?')"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
              </table>
        </div>
    </div>
</div>
