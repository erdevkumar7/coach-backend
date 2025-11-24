@extends('admin.layouts.layout')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('admin.addFaqs') }}" class="btn btn-outline-info btn-fw" style="float: right;">Add FAQs</a>
                        <h4 class="card-title">FAQs Management</h4>
                        <p class="card-description">FAQs List</p>

                        <div class="table-responsive">
                            <table class="table table-striped" id="faq-table">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Category</th>                                                                     
                                        <th>Position</th>
                                        <th>Title</th>        
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="sortable">
                                    @foreach($faqs as $faq)
                                    <tr data-id="{{ $faq->id }}" data-category="{{ $faq->category_id }}">
                                        <td>{{ ($faqs->currentPage() - 1) * $faqs->perPage() + $loop->iteration }}</td>                                     
                                        <td>{{ $faq->category_name }}</td>
                                        <td>{{ $faq->position }}</td>
                                       <td>{{ $faq->title }}</td>
                                        <td>
                                            <input type="checkbox" class="faq-toggle" data-id="{{ $faq->id }}" {{ $faq->is_active ? 'checked' : '' }}>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.addFaqs', ['id'=>$faq->id]) }}"><i class="mdi mdi-lead-pencil"></i></a> |
                                            <a href="javascript:void(0)" class="del_faq" data-id="{{ $faq->id }}"><i class="mdi mdi-delete"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

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
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script>
$(function() {

    $("#sortable").sortable({
        update: function(event, ui) {

            let order = [];

            let grouped = {};

            $('#sortable tr').each(function() {
                let id = $(this).data('id');
                let category = $(this).data('category');

                if (!grouped[category]) {
                    grouped[category] = [];
                }

                grouped[category].push(id);
            });

            Object.keys(grouped).forEach(categoryId => {

                grouped[categoryId].forEach((faqId, index) => {
                    order.push({
                        id: faqId,
                        category_id: categoryId,
                        position: index + 1
                    });
                });

            });

            $.ajax({
                url: "{{ route('admin.updateFaqPosition') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    order: order
                },
                success: function(res) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: res.message
                    }).then(() => location.reload());
                },
                error: function(err) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong!'
                    });
                }
            });
        }
    }).disableSelection();


    $('.faq-toggle').on('change', function() {
        const id = $(this).data('id');
        const value = $(this).is(':checked') ? 1 : 0;

        $.ajax({
            url: "{{ url('/admin/addFaqs/') }}",
            method: 'POST',
            data: { _token: '{{ csrf_token() }}', faq_id: id, status: value },
            success: function(response) {
                Swal.fire({
                    icon: response.success ? 'success' : 'error',
                    title: response.success ? 'Success' : 'Failed',
                    text: response.message
                });
            },
            error: function(err) { alert('Something went wrong.'); }
        });
    });


    // delete FAQ
    $(document).on('click', '.del_faq', function() {
        const id = $(this).data('id');
        const row = $(this).closest('tr');

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if(result.isConfirmed){
                $.ajax({
                    url: "{{ url('/admin/delete_faq') }}",
                    method: 'POST',
                    data: { _token: '{{ csrf_token() }}', id: id },
                    success: function(res) {
                        Swal.fire({ icon: 'success', title: 'Deleted!', text: res.message });
                        row.remove();
                    },
                    error: function(err) { alert('Delete failed.'); }
                });
            }
        });
    });

});

</script>
@endpush
