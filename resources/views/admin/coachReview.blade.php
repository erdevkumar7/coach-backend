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
                  <a href="{{route('admin.addcoachReview')}}" class="btn btn-outline-info btn-fw" style="float: right;">Add Coach Review</a>
                    <h4 class="card-title">Coach Review</h4>
                    <p class="card-description"> Coach Review List 
                    </p>
                    <form id="bulkDeleteForm" method="POST" action="{{ route('admin.DeletecoachReview') }}">
                      @csrf
                      <div class="table-responsive">
                        <table class="table table-striped" id="example">
                          <thead>
                            <tr>
                              <th><input type="checkbox" id="selectAll"></th>
                              <th>S.NO </th>
                              <th>Coach Name</th>
                              <th>Designation</th>
                              <th>Title </th>                            
                              <th>Description </th>                            
                              <th>Review</th>
                              <th>Status</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            @if($coachReview)
                           @php
                              $i = ($coachReview->currentPage() - 1) * $coachReview->perPage() + 1;
                          @endphp
                            @foreach($coachReview as $list)
                            <tr>
                              <td><input type="checkbox" name="ids[]" value="{{ $list->id }}" class="selectBox"></td>
                              <td>{{$i}}</td>
                              <td>{{$list->coach->first_name ?? ''}} {{$list->coach->last_name ?? ''}}</td>
                              <td>{{$list->designation ?? ''}}</td>
                              <td> {{$list->title ?? ''}} </td>
                              <td> {{$list->description ?? ''}} </td>
                              <td> {{$list->rating ?? ''}} </td>
                              <td><select class="review_status form-select form-select-sm" review="{{$list->id}}">
                                  <option value="0" {{$list->status==0?'selected':''}}>Inactive</option>
                                  <option value="1" {{$list->status==1?'selected':''}}>Active</option>                                
                                </select>
                              </td>
                              <td>                              
                                <a href="{{route('admin.addcoachReview')}}/{{ $list->id }}"><i class="mdi mdi-lead-pencil"></i></a></td>
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
                        {{ $coachReview->links('pagination::bootstrap-4') }}
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

                $('.review_status').on('change', function() {
                    let status = $(this).val();
                    let reviewId = $(this).attr('review');
                    $.ajax({
                        url: "{{ route('admin.updatecoachReviewstatus') }}",
                        method: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: reviewId,
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