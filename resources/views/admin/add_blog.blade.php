@extends('admin.layouts.layout')

@section('content')
<style>
  .ck-editor__editable {
    min-height: 300px !important; /* Or whatever height you want */
  }
</style>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-md-12 grid-margin stretch-card">
                <?php
                  $blog_name=$blog_id=$blog_content=$blog_image=$blog_video=$video_type="";  
                  if($blog_detail)
                  {
                    $blog_id=$blog_detail->id;
                    $blog_name=$blog_detail->blog_name;
                    $blog_content=$blog_detail->blog_content;
                    $blog_image=$blog_detail->blog_image;
                    $blog_video=$blog_detail->blog_video;    
                    $video_type=$blog_detail->video_type;    
                  }
                ?>
                <div class="card">
                  <div class="card-body">
                    <a href="{{route('admin.blogList')}}" class="btn btn-outline-info btn-fw" style="float: right;">Blog List</a>
                    <h4 class="card-title">Blog Management</h4>
                    <!--p class="card-description"> Add / Update Blog  </p-->
                    <form class="forms-sample" method="post" action="{{route('admin.addBlog')}}" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                      <div class="row">
                        <div class="form-group col-md-6">
                          <input type="hidden" name="id" value="{{$blog_id}}">
                          <label for="exampleInputUsername1">Blog Name</label>
                          <input required type="text" class="form-control form-control-sm" placeholder="Enter Blog Name" aria-label="Blogname" name="blog_name" value="{{$blog_name}}">
                        </div>                        
                        <div class="form-group col-md-12">
                          <label for="blog-content">Blog Content</label>
                          <textarea  class="form-control form-control-sm" id="blog-content" name="blog_content" placeholder="Enter blog content here..." >{{$blog_content}}</textarea>
                        </div>
                        <div class="form-group col-md-6">                          
                          <label for="exampleInputUsername1">Video Type</label>
                          <select class="form-select form-select-sm" name="video_type" id="video_type">
                            <option value="1" {{$video_type==1?'selected':''}}>Video URl</option>
                            <option value="2" {{$video_type==2?'selected':''}}>Video File</option>
                          </select>
                        </div>
                        <div class="form-group col-md-6" id="vurl">                          
                          <label for="exampleInputUsername1">Video Url</label>
                          <input type="text" class="form-control form-control-sm" placeholder="Enter Blog Name" aria-label="video-url" name="video_url" pattern="https?://.+" value="{{$video_type==1?$blog_video:''}}">
                        </div>  
                        <div class="form-group col-md-6" id="vfile">                          
                          <label for="exampleInputUsername1">Video File</label>
                          <input type="file" class="form-control form-control-sm document-input" name="video_file" accept=".mp4,.mov,.avi,.wmv,.mkv,.webm">
                          @if(!empty($blog_detail->blog_video))
                          @if($video_type==2)
                            <div class="mt-1 uploaded-file">
                              <a href="{{ asset('/public/uploads/blog_files/' . $blog_detail->blog_video) }}" target="_blank">{{ $blog_detail->blog_video }}</a>
                            </div>
                          @endif  
                          @endif
                        </div>
                        <div class="form-group col-md-6" >                          
                          <label for="exampleInputUsername1">Blog Image</label>
                          <input type="file" class="form-control form-control-sm document-input" name="blog_image" accept=".jpg,.jpeg,.jfif,.png,.webp">
                          @if(!empty($blog_detail->blog_image))
                            <div class="mt-1 uploaded-file">
                              <a href="{{ asset('/public/uploads/blog_files/' . $blog_detail->blog_image) }}" target="_blank">{{ $blog_detail->blog_image }}</a>
                            </div>
                          @endif
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