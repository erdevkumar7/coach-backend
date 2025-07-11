@extends('admin.layouts.layout')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                <h4 class="card-title">FAQs and Support </h4>
                <form id="" method="POST" action="">
                    @csrf
                    <div class="table-responsive">
                    <table class="table table-striped" id="faq-support">
                        <thead>
                        <tr>
                            {{-- <th><input type="checkbox" id="selectAll"></th> --}}
                            <th> Sr no </th>
                            <th> First name </th>
                            <th>Professional Title </th>
                            <th> Email </th>
                            <th> Comp. Name </th>
                            <th> Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @if($users)
                            @php $i=1; @endphp
                            @foreach($users as $user)
                            <tr>
                                <td>{{$i }}</td>
                                <td>{{$user->first_name }}</td>
                                <td>{{ $user->professional_title }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->company_name}}</td>
                                <td><button class="btn btn-outline-primary rounded-pill">View</button></td>
                            </tr>

                                @php $i++; @endphp
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    </div>
                </form>
                <div class="d-flex add-pagination mt-4">
                    {{-- {{ $users->links('pagination::bootstrap-4') }} --}}
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
            } );
    </script>
@endpush
