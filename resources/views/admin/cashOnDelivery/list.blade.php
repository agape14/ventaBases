<div class="card rounded" style="box-shadow: none !important">
    <div class="card-header">
        <h4>All Locations ( cash on delivery available )</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="dataTable">
                <thead class="bg-primary">
                  <tr>
                    <th>#</th>
                    <th>State Division</th>
                    <th>City</th>
                    <th>Township</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->stateDivision->name }}</td>
                            <td>{{ $item->city->name }}</td>
                            <td>{{ $item->township->name }}</td>
                            <td>{{ $item->status == '1' ? 'active' : 'unactive' }}</td>
                            <td>
                                <a href="{{ route('admin#editCos',$item->id ) }}" class="btn btn-success btn-sm"><i class="fas fa-edit"></i></a>
                                <a href="{{ route('admin#deleteCos',$item->id ) }}" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure to delete?')"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
              </table>
        </div>
    </div>
</div>
