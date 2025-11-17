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
            <h4 class="card-title">Social Media</h4>

            <form id="socialmedia" class="forms-sample" method="post" action="{{ route('admin.socialmedia') }}" enctype="multipart/form-data">
              @csrf
              <div class="row">

                <div class="form-group col-md-12">
                  <label>Facebook url</label>
                  <input required type="text" class="form-control form-control-sm" placeholder="Enter Facebook Url" name="facebook" value="{{ $socialmedia->facebook ?? '' }}">
                </div>

                 <div class="form-group col-md-12">
                  <label>Twitter (X) url</label>
                  <input required type="text" class="form-control form-control-sm" placeholder="Enter Twitter Url" name="twitter" value="{{ $socialmedia->twitter ?? '' }}">
                  </div>   
                  <div class="form-group col-md-12">
                  <label>Linkedin url</label>
                  <input required type="text" class="form-control form-control-sm" placeholder="Enter Linkedin Url" name="linkedin" value="{{ $socialmedia->linkedin ?? '' }}">
                  </div>   
                  <div class="form-group col-md-12">
                  <label>Instagram url</label>
                  <input required type="text" class="form-control form-control-sm" placeholder="Enter Instagram Url" name="instagram" value="{{ $socialmedia->instagram ?? '' }}">
                  </div>   
                  <div class="form-group col-md-12">
                  <label>Youtube url</label>
                  <input required type="text" class="form-control form-control-sm" placeholder="Enter Youtube Url" name="youtube" value="{{ $socialmedia->youtube ?? '' }}">
                  </div>


              </div>

              <button type="submit" class="btn btn-primary me-2">Update</button>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

  @push('scripts')
   


 @endpush
@endsection
