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
                  $title=$id=$description=$logo="";  
                  if($global_partners)
                  {
                    $id=$global_partners->id;
                    $title=$global_partners->title;
                    $description=$global_partners->description;
                    $logo=$global_partners->logo;
                  }
                ?>
                <div class="card">
                  <div class="card-body">
                    <a href="{{route('admin.globalPartnersList')}}" class="btn btn-outline-info btn-fw" style="float: right;">Global Partners List</a>
                    <h4 class="card-title">Global Partners</h4>                
                    <form class="forms-sample" method="post" action="{{route('admin.addGlobalPartners')}}" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                      <div class="row">
                        <div class="form-group col-md-12">
                          <input type="hidden" name="id" value="{{$id}}">
                          <label for="exampleInputUsername1">Title</label>
                          <input required type="text" class="form-control form-control-sm" placeholder="Enter Title" aria-label="Title" name="title" value="{{$title}}">
                        </div>                        
                        <!-- <div class="form-group col-md-12">
                          <label for="blog-content">Description</label>
                          <textarea  class="form-control form-control-sm" id="blog-content" name="description" placeholder="Enter description..." >{{$description}}</textarea>
                        </div>                         -->
                       
                        <div class="form-group col-md-6" >                          
                          <label for="exampleInputUsername1">Logo</label>
                          <input type="file" class="form-control form-control-sm document-input" name="logo" accept=".jpg,.jpeg,.jfif,.png,.webp">
                          @if(!empty($global_partners->logo))
                            <div class="mt-1 uploaded-file">
                              <a href="{{ asset('/public/uploads/blog_files/' . $global_partners->logo) }}" target="_blank">{{ $global_partners->logo }}</a>
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
        </div>
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