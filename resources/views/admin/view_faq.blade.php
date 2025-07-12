@extends('admin.layouts.layout')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                      <a href="{{route('admin.faqs.index')}}" class="btn btn-outline-info btn-fw" style="float: right;">FAQs List</a>
                      <h4 class="card-title">FAQs  Management </h4>
                      <p class="card-description">FAQs List</p>
                      @if (!empty($faq) && ($faq->title || $faq->content))
                        <div class="col-12 col-sm-6 col-md-6 col-xl-3 grid-margin stretch-card">
                            <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">{{ $faq->title ?? 'Untitled FAQ' }}</h4>
                                <div class="d-flex justify-content-between">
                                {!! $faq->content !!}
                                </div>
                            </div>
                            </div>
                        </div>
                      @endif
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>
@endsection

@push('scripts')

@endpush
