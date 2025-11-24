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
                  <!-- <a href="{{route('admin.coachProfile')}}" class="btn btn-outline-info btn-fw" style="float: right;">Add Coach</a> -->
                    <h4 class="card-title">Review Management</h4>
                    <p class="card-description"> Review List 
                    </p>
                    
                   <form id="bulkDeleteForm" method="POST" action="{{ route('admin.DeleteReview') }}">
                      @csrf
                      <div class="table-responsive">
                        <table class="table table-striped" id="example">
                          <thead>
                            <tr>
                              <th><input type="checkbox" id="selectAll"></th>
                              <th> Sr no </th>
                              <th> User Name </th>
                              <th> Coach Name </th>
                              <th> Review </th>
                              <!-- <th> Rating </th> -->
                              <th> Status</th>
                              <th> Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            @if($review_list)
                            @php $i=1; @endphp 
                            @foreach($review_list as $list)
                            <tr>
                              <td><input type="checkbox" name="ids[]" value="{{ $list->id }}" class="selectBox"></td>
                              <td>{{$i}}</td>
                              <td> {{$list->user_first_name}} {{$list->user_last_name}} </td>
                              <td>{{$list->coach_first_name}} {{$list->coach_last_name}}</td>
                             <td>{{ Str::words($list->review_text, 10, '...') }}</td>
                             
                              <td><select class="status form-select form-select-sm" user="{{$list->id}}">
                                  <option value="0" {{$list->status==0?'selected':''}}>Pending</option>
                                  <option value="1" {{$list->status==1?'selected':''}}>Approved</option>
                                  <option value="2" {{$list->status==2?'selected':''}}>Suspended</option>
                                </select>
                              </td>
                              <td>
                                 <a href="{{ route('admin.viewReview', ['id' => $list->id]) }}"><i class="mdi mdi mdi-eye"></i></a>
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
                        {{ $review_list->links('pagination::bootstrap-4') }}
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
 

          $(document).ready(function () {
            $(document).on('change','.status',function(){
              var status=$(this).val();
              var user_id=$(this).attr('user');
              $.ajax({
                url: "{{url('/admin/status')}}",
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

      
          });
          
        </script>

        <script>
              $(document).ready(function () {

                $('#selectAll').on('click', function() {
                    $('.selectBox').prop('checked', $(this).prop('checked'));
                });

                $('#bulkDeleteForm').on('submit', function(e) {
                    e.preventDefault();
                    let selected = $('.selectBox:checked').length;
                    if(selected === 0){
                        Swal.fire('Error','Please select at least one review to delete','error');
                        return false;
                    }
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'Selected reviews will be deleted!',
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