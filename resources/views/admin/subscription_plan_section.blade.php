@extends('admin.layouts.layout')
@section('content')
<style>
  .ck-editor__editable {
    min-height: 300px !important;
  }
</style>

<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">{{ ucfirst(str_replace('_', ' ', $type)) }} Section</h4>

            <form class="forms-sample" method="post" action="{{ route('admin.manageupdate', $type) }}" enctype="multipart/form-data">
              @csrf
              <div class="row">

                {{-- Title field for all types --}}
                <div class="form-group col-md-12">
                  <label>Title</label>
                  <input required type="text" class="form-control form-control-sm" placeholder="Enter Title" name="title" value="{{ $section->title ?? '' }}">
                </div>

                {{-- Only show subtitle and description for 'plan' --}}
                @if($type == 'plan')
                  <div class="form-group col-md-12">
                    <label>Subtitle</label>
                    <textarea class="form-control form-control-sm" name="subtitle" placeholder="Enter subtitle...">{{ $section->subtitle ?? '' }}</textarea>
                  </div>

                  <div class="form-group col-md-12">
                    <label>Description</label>
                    <textarea class="form-control form-control-sm" name="description" placeholder="Enter description...">{{ $section->description ?? '' }}</textarea>
                  </div>
                @endif

              </div>

              <button type="submit" class="btn btn-primary me-2">Submit</button>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
