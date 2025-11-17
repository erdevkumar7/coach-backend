@extends('admin.layouts.layout')
@section('content')
<style>
  i.mdi { font-size: 18px; }

  select.form-select-sm {
    padding: 3px 6px;
    font-size: 13px;
    font-weight: 500;
    color: #fff;
    border-radius: 5px;
    text-align: center;
  }

  .status-pending {
    background-color: #f1c40f !important; 
    color: #000 !important;
  }

  .status-resolved {
    background-color: #2ecc71 !important; 
  }

  .status-rejected {
    background-color: #e74c3c !important; 
  }
</style>

<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">             
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Coach-User Report</h4>
            <p class="card-description">Coach-User Report List </p>

            <div class="table-responsive">
              <table class="table table-striped" id="example">
                <thead>
                  <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>S.NO </th>
                    <th>Reported By Name </th>                            
                    <th>Reported Against Name</th>                            
                    <th>Reason for report </th>                            
                    <th>Reported Date</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  @if($reports)
                    @php
                      $i = ($reports->currentPage() - 1) * $reports->perPage() + 1;
                    @endphp
                    @foreach($reports as $list)
                      @php
                        $statusClass = $list->status == 0 ? 'status-pending' :
                                       ($list->status == 1 ? 'status-resolved' : 'status-rejected');
                      @endphp
                      <tr>
                        <td><input type="checkbox" name="ids[]" value="{{ $list->id }}" class="selectBox"></td>
                        <td>{{$i}}</td>
                        <td>{{ $list->reporter_first_name.' '.$list->reporter_last_name ?? '' }} ({{ $list->reported_by_type == 2 ? 'User' : ($list->reported_by_type == 3 ? 'Coach' : '') }})</td>
                        <td>{{ $list->reported_first_name.' '.$list->reported_last_name ?? '' }} ({{ $list->reported_against_type == 2 ? 'User' : ($list->reported_against_type == 3 ? 'Coach' : '') }})</td>
                        <td>{{ $list->reason ?? '' }}</td>
                        <td>{{ date('d-m-Y', strtotime($list->created_at)) ?? '' }}</td>

                        <td>
                          <select name="status"
                            class="form-select form-select-sm report-status {{ $statusClass }}"
                            data-id="{{ $list->id }}"
                            {{ in_array($list->status, [1,2]) ? 'disabled' : '' }}>
                            <option value="0" {{ $list->status == 0 ? 'selected' : '' }}>Pending</option>
                            <option value="1" {{ $list->status == 1 ? 'selected' : '' }}>Resolved</option>
                            <option value="2" {{ $list->status == 2 ? 'selected' : '' }}>Rejected</option>
                          </select>
                        </td>
                      </tr>
                      @php $i++; @endphp 
                    @endforeach
                  @endif
                </tbody>
              </table>
            </div>

            <div class="d-flex add-pagination mt-4">
              {{ $reports->links('pagination::bootstrap-4') }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
  $(document).ready(function() {
    $('.report-status').on('change', function() {
      let select = $(this);
      let id = select.data('id');
      let status = select.val();
      let oldClass = 'status-pending status-resolved status-rejected';
      select.removeClass(oldClass);

      if (status == 0) select.addClass('status-pending');
      if (status == 1) select.addClass('status-resolved');
      if (status == 2) select.addClass('status-rejected');

      Swal.fire({
        title: "Are you sure?",
        text: "You are about to change this report status.",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, update it!"
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: "{{ url('admin/reportstatus') }}/" + id,
            type: "POST",
            data: {
              _token: "{{ csrf_token() }}",
              status: status
            },
            success: function(response) {
              Swal.fire({
                icon: 'success',
                title: 'Updated!',
                text: 'Report status updated successfully.',
                timer: 1500,
                showConfirmButton: false
              });
              if (status == 1 || status == 2) {
                select.prop('disabled', true);
              }
            },
            error: function() {
              Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: 'Something went wrong while updating status.'
              });
            }
          });
        } else {
          select.val(select.data('previous'));
        }
      });
    });

    $('.report-status').each(function() {
      $(this).data('previous', $(this).val());
    });
  });
  </script>
@endsection
