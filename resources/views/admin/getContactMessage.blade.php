@extends('admin.layouts.layout')
@section('content')
<style>
.msg-col{
    white-space: normal !important;
    word-break: break-word;
    max-width: 300px;
}
</style>

<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Contact Messages</h4>
            <p class="card-description">List of all messages submitted from contact form</p>

            <form id="bulkDeleteForm" method="POST" action="{{ route('admin.DeleteContactMessage') }}">
              @csrf

              <div class="table-responsive">
                <table class="table table-striped" id="example">
                  <thead>
                    <tr>
                      <th><input type="checkbox" id="selectAll"></th>
                      <th>S.NO</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Phone</th>
                      <th>Subject</th>
                      <th style="width: 300px;">Message</th>
                      <th>Submitted Date</th>
                    </tr>
                  </thead>

                  <tbody>
                    @php
                      $i = ($getContactMessage->currentPage() - 1) * $getContactMessage->perPage() + 1;
                    @endphp

                    @foreach($getContactMessage as $msg)
                    <tr>
                      <td><input type="checkbox" name="ids[]" value="{{ $msg->id }}" class="selectBox"></td>
                      <td>{{ $i++ }}</td>
                      <td>{{ $msg->first_name }} {{ $msg->last_name }}</td>
                      <td>{{ $msg->email }}</td>
                      <td>{{ $msg->phone_number ?? '-' }}</td>
                      <td>{{ $msg->subject }}</td>
                      <td class="msg-col">{{ $msg->message }}</td>
                      <td>{{ \Carbon\Carbon::parse($msg->created_at)->format('d-m-Y') }}</td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>

              <button type="submit" class="btn btn-outline-danger mt-3">
                Delete Selected
              </button>
            </form>

            <div class="d-flex add-pagination mt-4">
              {{ $getContactMessage->links('pagination::bootstrap-4') }}
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
        Swal.fire('Error','Please select at least one message to delete','error');
        return false;
      }

      Swal.fire({
        title: 'Are you sure?',
        text: 'Selected messages will be deleted!',
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
