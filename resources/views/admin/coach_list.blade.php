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
                  <a href="{{route('admin.coachProfile')}}" class="btn btn-outline-info btn-fw" style="float: right;">Add Coach</a>
                    <h4 class="card-title">User Management</h4>
                    <p class="card-description"> Coach List 
                    </p>
                    
                    <form id="bulkDeleteForm" method="POST" action="{{ route('admin.bulkDeleteCoach') }}">
                      @csrf
                      <div class="table-responsive">
                        <table class="table table-striped" id="example">
                          <thead>
                            <tr>
                              <th><input type="checkbox" id="selectAll"></th>
                              <th> Sr no </th>
                              <th> First name </th>
                              <th> Last name </th>
                              <th> Email </th>
                              <th> Country </th>
                              <th> Status</th>
                              <th> Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            @if($users)
                            @php $i=1; @endphp 
                            @foreach($users as $list)
                            <tr>
                              <td><input type="checkbox" name="ids[]" value="{{ $list->id }}" class="selectBox"></td>
                              <td>{{$i}}</td>
                              <td> {{$list->first_name}} </td>
                              <td>{{$list->last_name}} </td>
                              <td> {{$list->email}}</td>
                              <td> {{$list->country_name}} </td>
                              <td><select class="user_status form-select form-select-sm" user="{{$list->id}}">
                                  <option value="0" {{$list->user_status==0?'selected':''}}>Pending</option>
                                  <option value="1" {{$list->user_status==1?'selected':''}}>Approved</option>
                                  <option value="2" {{$list->user_status==2?'selected':''}}>Suspended</option>
                                </select>
                              </td>
                              <td>
                                <a href="javascript:void(0)" class="del_user" user_id="{{$list->id}}"><i class="mdi mdi-delete"></i></a> | 
                                <a href="{{route('admin.coachProfile')}}/{{ $list->id }}"><i class="mdi mdi-lead-pencil"></i></a> |
                                <a href="{{ route('admin.viewCoach', ['id' => $list->id]) }}"><i class="mdi mdi mdi-eye"></i></a>
                                <!--a href="{{route('admin.addProfessional')}}/{{ $list->id }}"><i class="mdi mdi-professional-hexagon"></i></a-->
                              </td>
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
                        {{ $users->links('pagination::bootstrap-4') }}
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
                url: "{{url('/admin/update_status')}}",
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
                    text: "Status updated!",
                    icon: "success"
                  });
                },
                errror: function(xhr) {
                    console.log(xhr.responseText);
                  }
                });
            });

            $(document).on('click','.del_user',function(){
              const button = $(this);

              const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                  confirmButton: "btn btn-success",
                  cancelButton: "btn btn-danger"
                },
                buttonsStyling: false
              });
              swalWithBootstrapButtons.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
                reverseButtons: true
              }).then((result) => {
                if (result.isConfirmed) {

                  var user_id=$(this).attr('user_id');
                  $.ajax({
                    url: "{{url('/admin/delete_user')}}",
                    type: "POST",
                    datatype: "json",
                    data: {
                      user:user_id,
                      '_token':'{{csrf_token()}}'
                    },
                    success: function(result) {
                      
                      swalWithBootstrapButtons.fire({
                        title: "Deleted!",
                        text: "Caoch has been deleted.",
                        icon: "success"
                      });
                      button.closest('tr').remove();
                    },
                    errror: function(xhr) {
                        console.log(xhr.responseText);
                      }
                    });
                } else if (
                  /* Read more about handling dismissals below */
                  result.dismiss === Swal.DismissReason.cancel
                ) {
                  swalWithBootstrapButtons.fire({
                    title: "Cancelled",
                    text: "Your user is safe :)",
                    icon: "error"
                  });
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
                text: 'Please select at least one Coach to delete.',
              });
              return;
            }

            Swal.fire({
              title: 'Are you sure?',
              text: "Selected Coach will be deleted.",
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