@extends('admin.layouts.layout')

@section('content')

<!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">


              <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                  <a href="" class="btn btn-outline-info btn-fw" style="float: right;">Add Coach</a>
                    <h4 class="card-title">Coach Booking Management</h4>
                    <p class="card-description"> Coach List
                    </p>

                    <form id="" method="POST" action="">
                      @csrf
                      <div class="table-responsive">
                        <table class="table table-striped" id="booking-example">
                          <thead>
                            <tr>
                              {{-- <th><input type="checkbox" id="selectAll"></th> --}}
                              <th> Sr no </th>
                              <th> First name </th>
                              <th>Professional Title </th>
                              <th> Email </th>
                              <th> Comp. Name </th>
                              <th> Booking</th>
                              <th> Action</th>
                            </tr>
                          </thead>
                          <tbody>
                             @if($coaches)
                                @php $i=1; @endphp
                                @foreach($coaches as $coach)
                                <tr>
                                    <td>{{$i }}</td>
                                    <td>{{ $coach->first_name }}</td>
                                    <td>{{ $coach->professional_title }}</td>
                                    <td>{{ $coach->email }}</td>
                                    <td>{{ $coach->company_name}}</td>
                                    <td><a href="{{ route('admin.viewBooking',['id' => $coach->id]) }}" class='btn btn-outline-success rounded-pill'>Booking</a></td>
                                    <td><button class="btn btn-outline-primary rounded-pill">View</button></td>
                                </tr>

                                    @php $i++; @endphp
                                @endforeach
                            @endif
                          </tbody>
                        </table>
                      </div>
                      <button type="submit" class="btn btn-outline-danger mt-3" id="bulkDeleteBtn">Delete Selected</button>
                    </form>
                    <div class="d-flex add-pagination mt-4">
                        {{ $coaches->links('pagination::bootstrap-4') }}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- content-wrapper ends -->
        </div>
@endsection

@push('scripts')

<script>
          $(document).ready( function () {
            var table = $('#booking-example').DataTable( {
              "bPaginate": false,
              "bInfo": false,
            });
          } );
</script>
@endpush
