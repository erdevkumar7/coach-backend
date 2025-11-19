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
                    <h4 class="card-title">Newsletter</h4>
                    <p class="card-description"> Newsletter List 
                    </p>
                    <form id="bulkDeleteForm" method="POST" action="{{ route('admin.Deletenewsletter') }}">
                      @csrf
                      <div class="table-responsive">
                        <table class="table table-striped" id="example">
                          <thead>
                            <tr>
                              <th><input type="checkbox" id="selectAll"></th>
                              <th>S.NO </th>
                              <th>Email </th>   
                              <th>Subscribed Date</th>                         
                            </tr>
                          </thead>
                          <tbody>
                            @if($newsletter)
                           @php
                              $i = ($newsletter->currentPage() - 1) * $newsletter->perPage() + 1;
                          @endphp
                            @foreach($newsletter as $list)
                            <tr>
                              <td><input type="checkbox" name="ids[]" value="{{ $list->id }}" class="selectBox"></td>
                              <td>{{$i}}</td>
                              <td> {{$list->email ?? ''}} </td> 
                              <td> {{ date('d-m-Y', strtotime($list->created_at)) }} </td>
                            </tr>
                            @php $i++; @endphp 
                            @endforeach
                            @endif
                          </tbody>
                        </table>
                      </div>
                      <button type="submit" class="btn btn-outline-danger mt-3" id="bulkDeleteBtn">Delete Selected</button>
                      <a href="{{ route('admin.newsletter', ['export' => 'csv']) }}" 
                        class="btn btn-success mt-3">
                        Download CSV
                      </a>

                    </form>
                    <div class="d-flex add-pagination mt-4">
                        {{ $newsletter->links('pagination::bootstrap-4') }}
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
          @if(session('success'))
              if (!performance.getEntriesByType("navigation")[0].type.includes("back_forward")) {
                  Swal.fire({
                      title: "Success!",
                      text: "{{ session('success') }}",
                      icon: "success",
                      confirmButtonText: "OK"
                  });
              }
          @endif

        </script>
 
        
        @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "{{ session('error') }}",
                confirmButtonText: "OK"
              });
            });
        </script>
        @endif
   

        <script>
            $(document).ready(function () {

                $('#selectAll').on('click', function() {
                    $('.selectBox').prop('checked', $(this).prop('checked'));
                });

                $('#bulkDeleteForm').on('submit', function(e) {
                    e.preventDefault();
                    let selected = $('.selectBox:checked').length;
                    if(selected === 0){
                        Swal.fire('Error','Please select at least one Newsletter to delete','error');
                        return false;
                    }
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'Selected Newsletter will be deleted!',
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