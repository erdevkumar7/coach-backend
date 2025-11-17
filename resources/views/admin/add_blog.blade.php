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
                <?php
                  $blog_name=$blog_id=$blog_content=$blog_image="";  
                  if($blog_detail)
                  {
                    $blog_id=$blog_detail->id;
                    $blog_name=$blog_detail->blog_name;
                    $blog_content=$blog_detail->blog_content;
                    $blog_image=$blog_detail->blog_image;                  
                  }
                ?>
                <div class="card">
                  <div class="card-body">
                    <a href="{{route('admin.blogList')}}" class="btn btn-outline-info btn-fw" style="float: right;">Blog List</a>
                    <h4 class="card-title">Blog Management</h4>
                    <form class="forms-sample" method="post" action="{{route('admin.addBlog')}}" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                      <div class="row">                  
                        <div class="form-group col-md-12">
                          <input type="hidden" name="id" value="{{$blog_id}}">
                          <label for="exampleInputUsername1">Blog Name</label>
                          <input required type="text" class="form-control form-control-sm" placeholder="Enter Blog Name" aria-label="Blogname" name="blog_name" value="{{$blog_name}}">
                        </div>   
                          <div class="form-group col-md-6">                          
                          <label for="exampleInputUsername1">Select Coach</label>
                          <select class="form-select form-select-sm" name="coach_id" required>
                            <option value="">Select Coach</option>
                            @foreach($coachs as $coach)
                              <option value="{{$coach->id}}" {{$blog_detail && $blog_detail->coach_id==$coach->id?'selected':''}}>{{$coach->first_name.' '.$coach->last_name}}</option>
                            @endforeach
                          </select>
                        </div>
                          <div class="form-group col-md-6" >                          
                          <label for="exampleInputUsername1">Blog Image</label>
                          <input type="file" class="form-control form-control-sm document-input" name="blog_image" accept=".jpg,.jpeg,.jfif,.png,.webp" {{ !$blog_id ? 'required' : '' }}>
                          @if(!empty($blog_detail->blog_image))
                            <div class="mt-1 uploaded-file">
                              <a href="{{ asset('/public/uploads/blog_files/' . $blog_detail->blog_image) }}" target="_blank">{{ $blog_detail->blog_image }}</a>
                            </div>
                          @endif
                        </div>                     
                        <div class="form-group col-md-12">
                          <label for="blog-content">Blog Content</label>
                          <textarea required class="form-control form-control-sm" rows="10" name="blog_content" placeholder="Enter blog content here...">{{$blog_content}}</textarea>
                        </div>             
                        
                      </div>
                      <input type="hidden" name="user_time" value="" id="user_timezone">
                      <button type="submit" class="btn btn-primary me-2">Submit</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- content-wrapper ends -->
        </div>
        <!-- main-panel ends -->
        @endsection
        @push('scripts')
        <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
          <script>
              ClassicEditor
                  .create(document.querySelector('#blog-content'))
                  .catch(error => {
                      console.error(error);
                  });
          </script>

          <script>
            document.addEventListener('DOMContentLoaded', function () {
                const select = document.getElementById('video_type');
                const vurl = document.getElementById('vurl');
                const vfile = document.getElementById('vfile');

                function toggleVolCoach() {
                    if (select.value === '1') {
                        vurl.style.display = 'block';
                        vfile.style.display = 'none';
                    } else {
                        vurl.style.display = 'none';
                        vfile.style.display = 'block';
                    }
                }

                // Run on page load
                toggleVolCoach();

                // Run when the select changes
                select.addEventListener('change', toggleVolCoach);
            });
          </script>
          <script>
          $(document).on('change', '.document-input', function () {
            const file = this.files[0];
            const parent = $(this).closest('.form-group');
            
            parent.find('.uploaded-file').remove();
            parent.find('.new-upload-preview').remove();

            
              const fileName = file.name;
              const objectUrl = URL.createObjectURL(file); // temp file URL for preview

              const preview = `<div class="mt-1 new-upload-preview">
                                <a href="${objectUrl}" target="_blank">${fileName}</a>
                              </div>`;
              parent.append(preview);
            
          });
        </script>
        @endpush