@extends('admin.layouts.layout')
@section('content')
<style>
  i.mdi {
    font-size: 18px;
  }
</style>
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">             
              <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Coaching Request</h4>
                    <p class="card-description"> Coaching Request List 
                    </p>
                    <form id="bulkDeleteForm" method="POST" action="{{ route('admin.DeletecoachingRequest') }}">
                      @csrf
                      <div class="table-responsive">
                        <table class="table table-striped" id="example">
                          <thead>
                            <tr>
                              <th><input type="checkbox" id="selectAll"></th>
                              <th>S.NO </th>
                              <th>Coach Name</th>
                              <th>User Name</th>
                              <th>Category</th>
                              <th>Sub Category</th>
                              <th>Delivery Mode</th>
                              <th>Location</th>
                              <th>Goal</th>
                              <th>Request Date</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                        <tbody>
                            @if($coachingRequests && count($coachingRequests) > 0)
                                @php
                                    $i = ($coachingRequests->currentPage() - 1) * $coachingRequests->perPage() + 1;
                                @endphp

                                @foreach($coachingRequests as $list)
                                    <tr>
                                        <td><input type="checkbox" name="ids[]" value="{{ $list->id }}" class="selectBox"></td>
                                        <td>{{ $i }}</td>

                                        <td>{{ $list->coach?->first_name }} {{ $list->coach?->last_name }}</td>

                                        <td>{{ $list->user?->first_name }} {{ $list->user?->last_name }}</td>

                                        <td>{{ $list->lokingFor?->type_name ?? '-' }}</td>

                                        <td>{{ $list->coachingSubCategory?->subtype_name ?? '-'}}</td>

                                        <td>{{ $list->delivery_mode?->mode_name ?? '-' }}</td>
                                        <td>{{ $list->user->country->country_name ?? '-'}}</td>
                                        <td>{{ $list->coaching_goal ?? '-' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($list->created_at)->format('d-m-Y') }}</td>

                                        <td>
                                        <a class="btn btn-sm btn-danger" href="{{ url('public/uploads/coaching_requests/request_' . $list->id . '.pdf') }}" target="_blank">
                                          <i class="fa fa-file-pdf-o" ></i>
                                        </a>
                                        </td>
                                    </tr>
                                    @php $i++; @endphp
                                @endforeach

                                @else
                                    <tr>
                                        <td colspan="8" class="text-center">No coaching requests found.</td>
                                    </tr>
                                @endif
                        </tbody>
                        </table>
                      </div>
                         <button type="submit" class="btn btn-outline-danger mt-3" id="bulkDeleteBtn">Delete Selected</button>
                    </form>
                    <div class="d-flex add-pagination mt-4">
                        {{ $coachingRequests->links('pagination::bootstrap-4') }}
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
            $(document).ready(function () {

                $('#selectAll').on('click', function() {
                    $('.selectBox').prop('checked', $(this).prop('checked'));
                });

                $('#bulkDeleteForm').on('submit', function(e) {
                    e.preventDefault();
                    let selected = $('.selectBox:checked').length;
                    if(selected === 0){
                        Swal.fire('Error','Please select at least one Request to delete','error');
                        return false;
                    }
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'Selected Request will be deleted!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete!'
                    }).then((result) => {
                        if(result.isConfirmed){
                            this.submit();
                        }
                    });
                });


            });
        </script>
      
        @endpush