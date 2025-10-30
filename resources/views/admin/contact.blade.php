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
            <h4 class="card-title">Contact-Us</h4>

            <form id="contactForm" class="forms-sample" method="post" action="{{ route('admin.contact') }}" enctype="multipart/form-data">
              @csrf
              <div class="row">

                <div class="form-group col-md-12">
                  <label>Title</label>
                  <input required type="text" class="form-control form-control-sm" placeholder="Enter Title" name="title" value="{{ $contact->title ?? '' }}">
                </div>

                  <div class="form-group col-md-12">
                    <label>Subtitle</label>
                    <textarea class="form-control form-control-sm" name="subtitle" placeholder="Enter subtitle...">{{ $contact->subtitle ?? '' }}</textarea>
                  </div>                
        
                   <div class="form-group col-md-6">                          
                      <label>Image </label>
                        <input type="file" class="form-control form-control-sm document-input" name="image" 
                        accept=".jpg,.jpeg,.jfif,.png,.webp">

                         @if(!empty($contact->image))
                                  <div class="mt-1 uploaded-file">
                                      <a href="{{ asset('/public/uploads/blog_files/' . $contact->image) }}" target="_blank">
                                          {{ $contact->image }}
                                      </a>
                                  </div>
                          @endif
                    </div>
                    
                <div class="form-group col-md-12">
                  <label>Email</label>
                  <input required type="email" class="form-control form-control-sm" placeholder="Enter Email" name="email" value="{{ $contact->email ?? '' }}">
                </div>

                <div class="form-group col-md-12">
                <label>Address</label>
                <textarea class="form-control form-control-sm" id="address" name="address" placeholder="Enter Address...">{{ $contact->address ?? '' }}</textarea>
              </div>       
              
               <div class="form-group col-md-12">
                <label>Business Hours</label>
                <textarea class="form-control form-control-sm" name="business_hourse" placeholder="Enter Business Hourse...">{{ $contact->business_hourse ?? '' }}</textarea>
              </div>               

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
                  .create(document.querySelector('#address'))
                  .catch(error => {
                      console.error(error);
                  });
          </script>

          <script>
              $(document).ready(function () {
                  $('#contactForm').validate({
                      ignore: [], 
                      rules: {
                          title: { 
                              required: true, 
                              maxlength: 25 
                          },
                          subtitle: { 
                              required: true, 
                              maxlength: 50 
                          },
                          email: { 
                              required: true, 
                              email: true, 
                              maxlength: 255 
                          },
                          image: {
                            
                              extension: "jpg|jpeg|jfif|png|webp"
                          },
                          address: { 
                              required: true, 
                              maxlength: 255 
                          },
                          business_hourse: { 
                              required: true, 
                              maxlength: 255 
                          }
                      },
                      messages: {
                          title: { 
                              required: "Please enter the Title", 
                              maxlength: "Title cannot exceed 25 characters" 
                          },
                          subtitle: { 
                              required: "Please enter the SubTitle", 
                              maxlength: "Subtitle cannot exceed 50 characters" 
                          },
                          email: { 
                              required: "Please enter the Email", 
                              email: "Please enter a valid Email address", 
                              maxlength: "Email cannot exceed 255 characters"
                          },
                          image: {
                              extension: "Only JPG, JPEG, JFIF, PNG, and WEBP formats are allowed"
                          },
                          address: { 
                              required: "Please enter the Address", 
                              maxlength: "Address cannot exceed 255 characters"
                          },
                          business_hourse: { 
                              required: "Please enter the Business Hours", 
                              maxlength: "Business Hours cannot exceed 255 characters"
                          }
                      },
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
