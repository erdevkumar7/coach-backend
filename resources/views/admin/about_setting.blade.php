@extends('admin.layouts.layout')
@section('content')
<style>
  .ck-editor__editable {
    min-height: 300px !important;
  }

    .ck .ck-toolbar .ck-file-dialog-button {
        display: none !important;
    }
</style>

<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">{{ ucfirst(str_replace('_', ' ', $type)) }} Section</h4>

            <form id="aboutForm" class="forms-sample" method="post" action="{{ route('admin.aboutupdate', $type) }}" enctype="multipart/form-data">
              @csrf
              <div class="row">             
                
                @if($type == 'about_top')

                <!-- <div class="form-group col-md-12">
                  <label>Title</label>
                  <input required type="text" class="form-control form-control-sm" placeholder="Enter Title" name="title" value="{{ $section->title ?? '' }}">
                </div> -->

                  <div class="form-group col-md-12">
                    <label>Subtitle</label>
                    <textarea class="form-control form-control-sm" name="subtitle" placeholder="Enter subtitle...">{{ $section->subtitle ?? '' }}</textarea>
                  </div>

                   <!-- <div class="form-group col-md-6">                          
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

                    </div> -->
                @endif

           
                @if($type == 'jurney')      
                <div class="form-group col-md-12">
                  <label>Title</label>
                  <input required type="text" class="form-control form-control-sm" placeholder="Enter Title" name="title" value="{{ $section->title ?? '' }}">
                </div>
           
                  <div class="form-group col-md-12">
                    <label>Description</label>
                    <textarea class="form-control form-control-sm"  id="description" name="description" placeholder="Enter description...">{{ $section->description ?? '' }}</textarea>
                  </div>

                   <div class="form-group col-md-6">                          
                    <label>Video </label>
                      <input type="file" class="form-control form-control-sm document-input" name="video" 
                      accept=".mp4,.mov,.wmv,.avi,.mkv">

                        @if(!empty($section->video))
                                <div class="mt-1 uploaded-file">
                                    <a href="{{ asset('/public/uploads/blog_files/' . $section->video) }}" target="_blank">
                                        {{ $section->video }}
                                    </a>
                                </div>
                        @endif

                    </div>
             
                @endif       
                
               @if($type == 'team')      
                <div class="form-group col-md-12">
                  <label>Title</label>
                  <input required type="text" class="form-control form-control-sm" placeholder="Enter Title" name="title" value="{{ $section->title ?? '' }}">
                </div>
           
                  <div class="form-group col-md-12">
                    <label>Description</label>
                    <textarea class="form-control form-control-sm"  id="description" name="description" placeholder="Enter description...">{{ $section->description ?? '' }}</textarea>
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
        <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
          <script>
              ClassicEditor
                  .create(document.querySelector('#description'))
                  .catch(error => {
                      console.error(error);
                  });
          </script>
  <script>
      $(document).ready(function () {
          var type = "{{ $type }}";

          var rules = {};
          var messages = {};

          if(type === 'about_top') {
              rules = {
                  title: { required: true, maxlength: 25 },
                  subtitle: { required: true, maxlength: 40 },
              };
              messages = {
                  title: { required: "Please enter Title", maxlength: "Title cannot exceed 25 characters" },
                  subtitle: { maxlength: "Subtitle cannot exceed 40 characters" }
              };
          }

          else if(type === 'jurney' || type === 'team') {
              rules = {
                  title: { required: true, maxlength: 25 },
                  description: { required: true, maxlength: 1000 },
              };
              messages = {
                  title: { required: "Please enter Title", maxlength: "Title cannot exceed 25 characters" },
                  description: { maxlength: "Description cannot exceed 1000 characters" }
              };
          }
     

          $('#aboutForm').validate({
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
