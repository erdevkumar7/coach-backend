@extends('admin.layouts.layout')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
                <div class="row">
                    <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                                <h4 class="card-title">Coaching Request Management</h4>
                                <p class="card-description">Request List</p>
                                <div class="row">
                                    <div class="col-md-3 mb-4 stretch-card transparent">
                                        <div class="card card-tale">
                                            <div class="card-body">
                                            <p class="mb-4">Recent Requests</p>
                                            <p class="fs-30 mb-2">
                                                {{ isset($coach_req) ? $coach_req->count() : 0 }}
                                            </p>
                                            <p>(7 days)</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-4 stretch-card transparent">
                                        <div class="card card-dark-blue">
                                            <div class="card-body">
                                            <p class="mb-4">Pending Requests</p>
                                            <p class="fs-30 mb-2">61344</p>
                                            <p>22.00% (30 days)</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-4 stretch-card transparent">
                                        <div class="card card-light-blue">
                                            <div class="card-body">
                                            <p class="mb-4">Approved Requests</p>
                                            <p class="fs-30 mb-2">34040</p>
                                            <p>2.00% (30 days)</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-4 stretch-card transparent">
                                        <div class="card card-light-danger">
                                            <div class="card-body">
                                            <p class="mb-4">Reject Requests</p>
                                            <p class="fs-30 mb-2">50</p>
                                            <p>0.02% (30 days)</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <form id="" method="POST" action="">
                                    @csrf
                                    <div class="table-responsive">
                                    <table class="table table-striped" id="faq-support">
                                        <thead>
                                        <tr>
                                            <th><input type="checkbox" id="selectAll"/></th>
                                            <th class="text-start"> Sr no </th>
                                            <th>looking For </th>
                                            <th>Preferred Mode</th>
                                            <th>Location</th>
                                            <th>Language</th>
                                            <th>Coach Gender</th>
                                            <th>Experience</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @if (isset($coach_req) && $coach_req->count() > 0)
                                                @foreach ($coach_req as $key => $request)
                                                    <tr>
                                                        <td><input type="checkbox" name="select[]" value="{{ $request->id }}" /></td>
                                                        <td class="text-start">{{ $key + 1 }}</td>
                                                        <td>{{ $request->type_name }}</td>
                                                        <td>{{ $request->mode_name}}</td>
                                                        <td>{{ $request->country_name }}</td>
                                                        <td>{{ $request->language}}</td>
                                                        <td>{{ $request->coach_gender==1? "male" :"female"}}</td>
                                                        <td>{{ $request->coach_experience_level }}</td>
                                                        {{-- <td>
                                                            <a href="{{ route('admin.coachingRequest.edit', $request->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                                            <form action="{{ route('admin.coachingRequest.destroy', $request->id) }}" method="POST" style="display:inline-block;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                            </form>
                                                        </td> --}}
                                                        <td>
                                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="9" class="text-center">No coaching requests found.</td>
                                                </tr>


                                            @endif

                                        </tbody>
                                    </table>
                                    </div>
                                </form>
                            <div class="d-flex add-pagination mt-4">
                                {{-- {{ $users->links('pagination::bootstrap-4') }} --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
            $(document).ready( function () {

                var table = $('#faq-support').DataTable( {
                    "bPaginate": false,
                    "bInfo": false,
                });
            } );
    </script>
@endpush
