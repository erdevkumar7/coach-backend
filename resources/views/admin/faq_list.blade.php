@extends('admin.layouts.layout')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                      <a href="{{route('admin.addFaqs')}}" class="btn btn-outline-info btn-fw" style="float: right;">Add FAQs</a>
                      <h4 class="card-title">FAQs  Management </h4>
                      <p class="card-description">FAQs List</p>
                    <form id="" method="POST" action="">
                        @csrf
                        <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th><input type="checkbox" id="selectAll"/></th>
                                <th class="text-start"> Sr no </th>
                                <th> Faq Title </th>
                                <th>Category</th>
                                <th> Status</th>
                                <th> Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                @if($faqs)
                                @php
                                $i = ($faqs->currentPage() - 1) * $faqs->perPage() + 1;
                               @endphp
                                @foreach($faqs as $faq)
                                <tr>
                                    <td><input type="checkbox" name="ids[]" value="{{ $faq->id }}" class="selectBox"></td>
                                    <td class="text-start">{{$i}}</td>
                                    <td>{{$faq->title }}</td>
                                    <td>{{ $faq->category_name }}</td>
                                    <td>
                                        <div class="form-check form-switch custom-switch">
                                            <span>{{$faq->is_active ==1?'Active':'Inactive'}}</span>
                                            <input class="form-check-input faq-toggle"
                                                type="checkbox"
                                                name="faq_toggle"
                                                id={{ $faq->id }}
                                                {{$faq->is_active ==1?' checked':''}}
                                            >
                                        </div>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0)" class="del_faq" id="{{$faq->id}}"><i class="mdi mdi-delete"></i></a> |
                                        <a href="{{route('admin.addFaqs',['id' => $faq->id])}}"><i class="mdi mdi-lead-pencil"></i></a>
                                </td>
                                </tr>
                                    @php $i++; @endphp
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        </div>
                    </form>
                <div class="d-flex add-pagination mt-4">
                {{ $faqs->links('pagination::bootstrap-4') }} 
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
            $(document).ready( function () {

                var table = $('#faq-support').DataTable( {
                    "bPaginate": false,
                    "bInfo": false,
                });

                $('.faq-toggle').on('change', function () {
                    const id=$(this).attr('id');
                    const value = $(this).is(':checked') ? 1 : 0;

                    $.ajax({
                        url: "{{url('/admin/addFaqs/')}}",
                        method: 'POST',
                        datatype: "json",
                        data: {
                            _token: '{{ csrf_token() }}',
                            faq_id: id,
                            status: value
                        },
                        success: function (response) {
                            console.log('Notification setting updated:', response);
                             if (response.success) {
                                Swal.fire({
                                    title: 'Success!',
                                    text: response.message,
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                });
                                } else {
                                    Swal.fire({
                                        title: 'Failed!',
                                        text: response.message,
                                        icon: 'error',
                                        confirmButtonText: 'Close'
                                    });
                                }
                        },
                        error: function (xhr) {
                            console.error('Update failed:', xhr.responseText);
                            alert('Something went wrong. Try again.');
                        }
                    });
                });

                $(document).on('click','.del_faq',function(){
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
                            var faq_id=$(this).attr('id');

                            $.ajax({
                                url: "{{url('/admin/delete_faq')}}",
                                type: "POST",
                                datatype: "json",
                                data: {
                                    id:faq_id,
                                    '_token':'{{csrf_token()}}'
                                    },
                                success: function(result) {

                                    console.log(result);
                                swalWithBootstrapButtons.fire({
                                    title: "Deleted!",
                                    text: result.message,
                                    icon: "success"
                                });
                                button.closest('tr').remove();
                                },
                                errror: function(xhr) {
                                    console.log(xhr.responseText);
                                }
                            });
                        }
                    });
                });

            } );
    </script>
@endpush
