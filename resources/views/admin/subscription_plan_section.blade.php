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

            <form id="homeSectionForm" class="forms-sample" method="post" action="{{ route('admin.manageupdate', $type) }}" enctype="multipart/form-data">
              @csrf
              <div class="row">

              
                
                @if($type == 'top' || $type == 'corporate')

                <div class="form-group col-md-12">
                  <label>Title</label>
                  <input required type="text" class="form-control form-control-sm" placeholder="Enter Title" name="title" value="{{ $section->title ?? '' }}">
                </div>

                  <div class="form-group col-md-12">
                    <label>Subtitle</label>
                    <textarea class="form-control form-control-sm" name="subtitle" placeholder="Enter subtitle...">{{ $section->subtitle ?? '' }}</textarea>
                  </div>
                @endif

               @if($type == 'global_partners')

                <div class="form-group col-md-12">
                  <label>Title</label>
                  <input required type="text" class="form-control form-control-sm" placeholder="Enter Title" name="title" value="{{ $section->title ?? '' }}">
                </div>

                @endif

                @if($type == 'plan')

                <div class="form-group col-md-12">
                  <label>Title</label>
                  <input required type="text" class="form-control form-control-sm" placeholder="Enter Title" name="title" value="{{ $section->title ?? '' }}">
                </div>

                  <div class="form-group col-md-12">
                    <label>Subtitle</label>
                    <textarea class="form-control form-control-sm" name="subtitle" placeholder="Enter subtitle...">{{ $section->subtitle ?? '' }}</textarea>
                  </div>

                  <div class="form-group col-md-12">
                    <label>Description</label>
                    <textarea class="form-control form-control-sm" name="description" placeholder="Enter description...">{{ $section->description ?? '' }}</textarea>
                  </div>
                @endif

                @if($type == 'middle_one' || $type == 'middle_two')      
                <div class="form-group col-md-12">
                  <label>Title</label>
                  <input required type="text" class="form-control form-control-sm" placeholder="Enter Title" name="title" value="{{ $section->title ?? '' }}">
                </div>
           
                  <div class="form-group col-md-12">
                    <label>Description</label>
                    <textarea class="form-control form-control-sm" name="description" placeholder="Enter description...">{{ $section->description ?? '' }}</textarea>
                  </div>
                    <div class="form-group col-md-6">                          
                      <label>Image </label>
                        <input type="file" class="form-control form-control-sm document-input" name="image" 
                        accept=".jpg,.jpeg,.jfif,.png,.webp">

                         @if(!empty($section->image))
                                  <div class="mt-1 uploaded-file">
                                      <a href="{{ asset('/public/uploads/blog_files/' . $section->image) }}" target="_blank">
                                          {{ $section->image }}
                                      </a>
                                  </div>
                              @endif

                    </div>
                @endif

                @if($type == 'footer_one')
                  <div class="form-group col-md-12">
                    <label>Description</label>
                    <textarea class="form-control form-control-sm" name="description" placeholder="Enter description...">{{ $section->description ?? '' }}</textarea>
                  </div>
                @endif

                   @if($type == 'footer_two')

                <div class="form-group col-md-12">
                  <label>Title</label>
                  <input required type="text" class="form-control form-control-sm" placeholder="Enter Title" name="title" value="{{ $section->title ?? '' }}">
                </div>

                @endif

              @if($type == 'category')

                <div class="form-group col-md-12">
                  <label>Title</label>
                  <input required type="text" class="form-control form-control-sm" placeholder="Enter Title" name="title" value="{{ $section->title ?? '' }}">
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

  @push('scripts')

  <script>
      $(document).ready(function () {
          var type = "{{ $type }}";

          var rules = {};
          var messages = {};

          if(type === 'top' || type === 'corporate') {
              rules = {
                  title: { required: true, maxlength: 255 },
                  subtitle: { maxlength: 255 }
              };
              messages = {
                  title: { required: "Please enter Title", maxlength: "Title cannot exceed 255 characters" },
                  subtitle: { maxlength: "Subtitle cannot exceed 255 characters" }
              };
          }
          else if(type === 'global_partners') {
              rules = {
                  title: { required: true, maxlength: 255 }
              };
              messages = {
                  title: { required: "Please enter Title", maxlength: "Title cannot exceed 255 characters" }
              };
          }
          else if(type === 'plan') {
              rules = {
                  title: { required: true, maxlength: 255 },
                  subtitle: { maxlength: 255 },
                  description: { maxlength: 1000 }
              };
              messages = {
                  title: { required: "Please enter Title", maxlength: "Title cannot exceed 255 characters" },
                  subtitle: { maxlength: "Subtitle cannot exceed 255 characters" },
                  description: { maxlength: "Description cannot exceed 1000 characters" }
              };
          }
          else if(type === 'middle_one' || type === 'middle_two') {
              rules = {
                  title: { required: true, maxlength: 255 },
                  description: { maxlength: 1000 }
              };
              messages = {
                  title: { required: "Please enter Title", maxlength: "Title cannot exceed 255 characters" },
                  description: { maxlength: "Description cannot exceed 1000 characters" }
              };
          }
          else if(type === 'footer_one') {
              rules = {
                  description: { required: true, maxlength: 1000 }
              };
              messages = {
                  description: { required: "Please enter Description", maxlength: "Description cannot exceed 1000 characters" }
              };
          }
          else if(type === 'footer_two' || type === 'category') {
              rules = {
                  title: { required: true, maxlength: 255 },
                  description: { maxlength: 1000 }
              };
              messages = {
                  title: { required: "Please enter Title", maxlength: "Title cannot exceed 255 characters" },
                  description: { maxlength: "Description cannot exceed 1000 characters" }
              };
          }

          $('#homeSectionForm').validate({
              ignore: [], 
              rules: rules,
              messages: messages,
              errorElement: "span",
              errorClass: "text-danger d-block",
              highlight: function(element) {
                  $(element).addClass("is-invalid");
              },
              unhighlight: function(element) {
                  $(element).removeClass("is-invalid");
              }
          });
      });

  </script>
  @endpush

@endsection
