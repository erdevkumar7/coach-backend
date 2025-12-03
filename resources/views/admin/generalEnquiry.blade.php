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
                    <h4 class="card-title">Enquiry List</h4>
                    <p class="card-description">Enquiry List 
                    </p>
                      <div class="table-responsive">
                        <table class="table table-striped" id="example">
                          <thead>
                            <tr>
                              <th>S.NO </th>
                              <th>User Name </th>                            
                              <th>Coach Name </th>                            
                              <th>User Email </th>                            
                              <th>Coach Email </th>                            
                              <th>Enquiry Message</th>
                              <th>Enquiry Date</th>
                            </tr>
                          </thead>
                          <tbody>
                            @if($generalEnquiry)
                           @php
                              $i = ($generalEnquiry->currentPage() - 1) * $generalEnquiry->perPage() + 1;
                          @endphp
                            @foreach($generalEnquiry as $list)
                            <tr>
                              <td>{{$i}}</td>
                              <td> {{$list->user_first_name.' '.$list->user_last_name ?? ''}} </td>
                                <td> {{$list->coach_first_name.' '.$list->coach_last_name ?? ''}} </td>
                              <td> {{$list->user_email ?? ''}} </td>
                              <td> {{$list->coach_email ?? ''}} </td>
                              <td> {{$list->message ?? ''}} </td>
                              <td>{{ date('d-m-Y', strtotime($list->created_at)) ?? '' }}</td>
                            </tr>
                            @php $i++; @endphp 
                            @endforeach
                            @endif
                          </tbody>
                        </table>
                      </div>
                      <div class="d-flex add-pagination mt-4">
                          {{ $generalEnquiry->links('pagination::bootstrap-4') }}
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

      
            });
        </script>

        @endpush