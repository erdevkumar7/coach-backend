@extends('admin.layouts.layout')

@section('content')
<style>
  i.mdi {
    font-size: 18px;
  }
</style>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              
              
              <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                  <a href="{{route('admin.addService')}}" class="btn btn-outline-info btn-fw" style="float: right;">Add Service</a>
                    <h4 class="card-title">Service Management</h4>
                    <p class="card-description"> Service List 
                    </p>
                    <form id="bulkDeleteForm" method="POST" action="{{ route('admin.bulkDeleteService') }}">
                      @csrf
                      <div class="table-responsive">
                        <table class="table table-striped" id="example">
                          <thead>
                            <tr>
                              <th><input type="checkbox" id="selectAll"></th>
                              <th> Sr no </th>
                              <th> Service name </th>                            
                              <th> Status</th>
                              <th> Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            @if($services)
                            @php $i=1; @endphp 
                            @foreach($services as $list)
                            <tr>
                              <td><input type="checkbox" name="ids[]" value="{{ $list->id }}" class="selectBox"></td>
                              <td>{{$i}}</td>
                              <td> {{$list->service}} </td>
                              
                              <td><select class="user_status form-select form-select-sm" user="{{$list->id}}">
                                  <option value="0" {{$list->is_active==0?'selected':''}}>Inactive</option>
                                  <option value="1" {{$list->is_active==1?'selected':''}}>Active</option>                                
                                </select>
                              </td>
                              <td>                              
                                <a href="{{route('admin.addService')}}/{{ $list->id }}"><i class="mdi mdi-lead-pencil"></i></a></td>
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
                        {{ $services->links('pagination::bootstrap-4') }}
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

        @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    title: "Success!",
                    text: "{{ session('success') }}",
                    icon: "success",
                    confirmButtonText: "OK"
                });
            });
        </script>
        @endif
        
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
          	
          
          $(document).ready( function () {
            var table = $('#example').DataTable( {
              "bPaginate": false,
              "bInfo": false,
            });
          } );


          $(document).ready(function () {
            $(document).on('change','.user_status',function(){
              var status=$(this).val();
              var user_id=$(this).attr('user');
              $.ajax({
                url: "{{url('/admin/update_service_status')}}",
                type: "POST",
                datatype: "json",
                data: {
                  status: status,
                  user:user_id,
                  '_token':'{{csrf_token()}}'
                },
                success: function(result) {
                  Swal.fire({
                    title: "Success!",
                    text: "Service Status updated!",
                    icon: "success"
                  });
                },
                errror: function(xhr) {
                    console.log(xhr.responseText);
                  }
                });
            });
            
          });
          
        </script>

        <script>
          document.getElementById('selectAll').addEventListener('click', function (e) {
            let checkboxes = document.querySelectorAll('.selectBox');
            checkboxes.forEach(cb => cb.checked = e.target.checked);
          });
          
          document.getElementById('bulkDeleteBtn').addEventListener('click', function (e) {
            e.preventDefault(); // Stop normal form submit

            const form = document.getElementById('bulkDeleteForm');
            const checkboxes = document.querySelectorAll('.selectBox:checked');

            if (checkboxes.length === 0) {
              Swal.fire({
                icon: 'warning',
                title: 'No selection',
                text: 'Please select at least one service to delete.',
              });
              return;
            }

            Swal.fire({
              title: 'Are you sure?',
              text: "Selected Service will be deleted.",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#d33',
              cancelButtonColor: '#3085d6',
              confirmButtonText: 'Yes, delete selected'
            }).then((result) => {
              if (result.isConfirmed) {
                form.submit(); // Submit the form only if confirmed
              }
            });
          });
        </script>
        @endpush