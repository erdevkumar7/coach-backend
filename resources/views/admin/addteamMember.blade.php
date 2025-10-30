@extends('admin.layouts.layout')

@section('content')
<style>
  .ck-editor__editable { min-height: 300px !important; }
</style>

<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">{{ isset($teamMember->id) ? 'Edit Team Member' : 'Add Team Member' }}</h4>

            <form id="teamMemberForm" method="post" action="{{ route('admin.addteamMember', $teamMember->id ?? '') }}" enctype="multipart/form-data">
              @csrf

              <div class="form-group col-md-12">
                <label>Name</label>
                <input required type="text" class="form-control form-control-sm" placeholder="Enter Name" name="name" value="{{ old('name', $teamMember->name ?? '') }}">
                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
              </div>

              <div class="form-group col-md-12">
                <label>Designation</label>
                <input required type="text" class="form-control form-control-sm" placeholder="Enter Designation" name="designation" value="{{ old('designation', $teamMember->designation ?? '') }}">
                @error('designation') <span class="text-danger">{{ $message }}</span> @enderror
              </div>

              <div class="form-group col-md-6">
                <label>Image</label>
                <input type="file" class="form-control form-control-sm" name="image" accept=".jpg,.jpeg,.jfif,.png,.webp">
                @error('image') <span class="text-danger">{{ $message }}</span> @enderror

                 @if(!empty($teamMember->image))
                   <div class="mt-1 uploaded-file">
                     <a href="{{ asset('/public/uploads/blog_files/' . $teamMember->image) }}" target="_blank">
                       {{ $teamMember->image }}
                       </a>
                    </div>
                    
                  @endif
              </div>

              <div class="form-group col-md-12">
                <label>Description</label>
                <textarea class="form-control form-control-sm" id="description" name="description" placeholder="Enter description...">{{ old('description', $teamMember->description ?? '') }}</textarea>
                @error('description') <span class="text-danger">{{ $message }}</span> @enderror
              </div>

              <button type="submit" class="btn btn-primary me-2">{{ isset($teamMember->id) ? 'Update' : 'Submit' }}</button>
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
      ClassicEditor.create(document.querySelector('#description')).catch(error => console.error(error));
    </script>

    <script>
      $(document).ready(function () {
           let isEdit = @json(isset($teamMember->id));
        $('#teamMemberForm').validate({
          ignore: [],
          rules: {
            name: { required: true, maxlength: 25 },
            designation: { required: true, maxlength: 50 },
            image: { required: !isEdit,extension: "jpg|jpeg|jfif|png|webp" },
            description: { required: true, maxlength: 255 },
          },
          messages: {
            name: { required: "Please enter the Name", maxlength: "Name cannot exceed 25 characters" },
            designation: { required: "Please enter the Designation", maxlength: "Designation cannot exceed 50 characters" },
            image: {  required: "Please upload an image", extension: "Only JPG, JPEG, JFIF, PNG, and WEBP formats are allowed" },
            description: { required: "Please enter the Description", maxlength: "Description cannot exceed 255 characters" },
          },
          errorElement: "span",
          errorClass: "text-danger d-block",
          highlight: function(element) { $(element).addClass("is-invalid"); },
          unhighlight: function(element) { $(element).removeClass("is-invalid"); }
        });
      });
    </script>
  @endpush
@endsection
