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
                    <h4 class="card-title">Coaching activities</h4>
                    <p class="card-description"> Coaching activities List 
                    </p>                 
                      <div class="table-responsive">
                        <table class="table table-striped" id="example">
                          <thead>
                             <tr>
                                    <th>S.No</th>
                                    <th>User Name</th>
                                    <th>User Email</th>
                                    <th>Coach Name</th>
                                    <th>Package Name</th>
                                    <th>Session Date & Time</th>
                                    <th>Status</th>
                                </tr>
                          </thead>
                          <tbody>

                                @php
                                    $i = ($bookings->currentPage() - 1) * $bookings->perPage() + 1;
                                @endphp

                                @foreach($bookings as $booking)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $booking->user->first_name }} {{ $booking->user->last_name }}</td>
                                        <td>{{ $booking->user->email ?? '' }}</td>
                                        <td>{{ $booking->coach->first_name ?? 'N/A' }} {{ $booking->coach->last_name ?? 'N/A' }}</td>
                                        <td>{{ $booking->coachPackage->title }}</td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($booking->session_date_start)->format('d-m-Y') }} |
                                            <strong>{{ \Carbon\Carbon::parse($booking->slot_time_start)->format('h:i A') }}</strong>
                                        </td>

                                       <td>
                                          @if($booking->status == 0)
                                              <span class="badge bg-warning text-dark">Pending</span>
                                          @elseif($booking->status == 1)
                                              <span class="badge bg-primary">Confirmed</span>
                                          @elseif($booking->status == 2)
                                              <span class="badge bg-success">Completed</span>
                                          @elseif($booking->status == 3)
                                              <span class="badge bg-danger">Cancelled</span>
                                          @else
                                              <span class="badge bg-secondary">Unknown</span>
                                          @endif
                                      </td>

                                    </tr>
                                @endforeach
                                </tbody>
                        </table>
                      </div>
                    <div class="d-flex add-pagination mt-4">
                        {{ $bookings->links('pagination::bootstrap-4') }}
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
                        Swal.fire('Error','Please select at least one member to delete','error');
                        return false;
                    }
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'Selected members will be deleted!',
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

                $('.member_status').on('change', function() {
                    let status = $(this).val();
                    let memberId = $(this).attr('member');
                    $.ajax({
                        url: "{{ route('admin.updateTeamMemberStatus') }}",
                        method: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: memberId,
                            status: status
                        },
                        success: function(response){
                            if(response.status){
                                Swal.fire('Success', response.message, 'success');
                            } else {
                                Swal.fire('Error', response.message, 'error');
                            }
                        }
                    });
                });
            });
        </script>

        @endpush