@extends('admin.layouts.layout')

@section('content')
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-md-12 grid-margin stretch-card">

                <div class="card">
                  <div class="card-body">
                      <a href="" class="btn btn-outline-info btn-fw" style="float: right;">Coaching Request List</a>
                      <h4 class="card-title">Coaching Request</h4>

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                      <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Basic Profile</button>
                      </li>
                      <li class="nav-item" role="presentation">
                        <button class="nav-link" id="eligible-coach-tab" data-bs-toggle="tab" data-bs-target="#eligible-coach-table" type="button" role="tab" aria-controls="eligible-coach-table" aria-selected="false">Eligible Coach</button>
                      </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">

                        <div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div class="row">
                                <div class="form-group col-md-6">
                                <input type="hidden" name="user_id" value="{{ $user->id ?? '' }}">
                                <label><strong>User Name : </strong> {{ $coach_req->first_name ?? '-' }}</label>
                                </div>

                                {{-- <div class="form-group col-md-6">
                                <label><strong>Email address : </strong> {{ $coach_req->email ?? '-' }}</label>
                                </div>

                                <div class="form-group col-md-6">
                                <label><strong>Contact Number: </strong> {{ $coach_req->contact_number ?? '-' }}</label>
                                </div>

                                <div class="form-group col-md-6">
                                <label><strong>Gender : </strong> {{ $coach_req->gender ?? '-' }}</label>
                                </div>

                                <div class="form-group col-md-6">
                                <label><strong>Your Profession: </strong> {{ $coach_req->profession ?? '-' }}</label>
                                </div>

                                <div class="form-group col-md-6">
                                <label><strong>Age Group: </strong> {{ $coach_req->age_group_name ?? '-' }}</label>
                                </div>

                                <div class="form-group col-md-6">
                                <label><strong>Preferred Coaching Time: </strong> {{ $coach_req->preferred_time ?? '-' }}</label>
                                </div>

                                <div class="form-group col-md-6">
                                <label><strong>Preferred Language Number: </strong> {{ $coach_req->preferred_language ?? '-' }}</label>
                                </div>

                                <div class="form-group col-md-6">
                                <label><strong>Preferred Mode: </strong> {{ $coach_req->preferred_mode ?? '-' }}</label>
                                </div>

                                <div class="form-group col-md-6">
                                <label><strong>Country : </strong> {{ $user->country_name ?? '-' }}</label>
                                </div>

                                <div class="form-group col-md-6">
                                <label><strong>State : </strong> {{ $user->state_name ?? '-' }}</label>
                                </div>

                                <div class="form-group col-md-6">
                                <label><strong>City : </strong> {{ $user->city_name ?? '-' }}</label>
                                </div> --}}
                            </div>
                        </div>




                        <div class="tab-pane" id="eligible-coach-table" role="tabpanel" aria-labelledby="eligible-coach-tab">
                            <div class="table-responsive">
                            <table class="table table-striped" id="eligible-coach-table-content">
                                <thead>
                                <tr>
                                    <th><input type="checkbox" id="selectAll"/></th>
                                    <th class="text-start"> Sr no </th>
                                    <th> Name </th>
                                    <th> Email </th>
                                    <th> Contact </th>

                                </tr>
                                </thead>
                                <tbody>
                                    @if ($eligible_coaches)
                                        @php $i=1; @endphp
                                        @foreach ($eligible_coaches as $coach)
                                            <tr>
                                                <td><input type="checkbox" name="ids[]" value="{{ $coach['id'] }}" class="selectBox"></td>
                                                <td class="text-start">{{ $i }}</td>
                                                <td>{{ $coach['display_name'] }}</td>
                                                <td>{{ $coach['email'] }}</td>
                                                <td>{{ $coach['contact_number'] }}</td>
                                            </tr>

                                            @php $i++; @endphp
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                            </div>
                      </div>

                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- content-wrapper ends -->
        </div>
        <!-- main-panel ends -->
@endsection

@push('scripts')
    <script>
    $(document).ready( function () {

        var table = $('#eligible-coach-table-content').DataTable( {
            "bPaginate": false,
            "bInfo": false,
        });

    } );
    </script>

@endpush
