@extends('admin.layouts.layout')
@section('content')
<style>
  .ck-editor__editable {
    min-height: 300px !important;
  }
</style>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <!-- Success Message Display -->
      @if(session('success'))
        <script>
          Swal.fire({
            title: 'Success!',
            text: "{{ session('success') }}",
            icon: 'success',
            confirmButtonText: 'Ok'
          });
        </script>
      @endif

      

      <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Update Profile</h4>

            <form id="settingForm" class="forms-sample" method="post" 
                  action="{{ route('admin.setting') }}" enctype="multipart/form-data">
              @csrf

              <div class="row">
                <div class="form-group col-md-12">
                  <label>First Name</label>
                  <input type="text" name="first_name" required 
                         class="form-control form-control-sm" 
                         placeholder="Enter First Name"
                         value="{{ old('first_name', $admin->first_name) }}">
                </div>

                <div class="form-group col-md-12">
                  <label>Last Name</label>
                  <input type="text" name="last_name" required 
                         class="form-control form-control-sm" 
                         placeholder="Enter Last Name"
                         value="{{ old('last_name', $admin->last_name) }}">
                </div>

                <div class="form-group col-md-12">
                  <label>Email</label>
                  <input type="email" name="email" required 
                         class="form-control form-control-sm" 
                         placeholder="Enter Email"
                         value="{{ old('email', $admin->email) }}">
                           @error('email')
                              <div class="text-danger mt-1">{{ $message }}</div>
                          @enderror
                </div>

                <div class="form-group col-md-6">
                  <label>Profile Image</label>
                  <input type="file" class="form-control form-control-sm" 
                         name="profile_image" accept="image/*">

                  @if(!empty($admin->profile_image))
                    <div class="mt-2">
                      <div>
                        <a href="{{ asset('public/uploads/blog_files/' . $admin->profile_image) }}" target="_blank">
                          {{ $admin->profile_image }}
                        </a>
                      </div>
                    </div>
                  @endif
                </div>
              </div>

              <button type="submit" class="btn btn-primary me-2">Submit</button>
            </form>

          </div>
        </div>
      </div>

      <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Change Password</h4>

            <form id="changePasswordForm" class="forms-sample" method="post" 
                  action="{{ route('admin.setting') }}" enctype="multipart/form-data">
              @csrf
              <div class="row">
                <div class="form-group col-md-12">
                  <label>Current Password</label>
                  <input type="password" name="current_password" 
                         class="form-control form-control-sm" 
                         placeholder="Enter Current Password">
                           @error('current_password')
                              <div class="text-danger mt-1">{{ $message }}</div>
                          @enderror
                </div>

                <div class="form-group col-md-12">
                  <label>New Password</label>
                  <input type="password" name="new_password" 
                         class="form-control form-control-sm" 
                         placeholder="Enter New Password">
                          @error('new_password')
                              <div class="text-danger mt-1">{{ $message }}</div>
                          @enderror
                </div>

                <div class="form-group col-md-12">
                  <label>Confirm Password</label>
                  <input type="password" name="new_password_confirmation" 
                         class="form-control form-control-sm" 
                         placeholder="Confirm New Password">
                            @error('new_password_confirmation')
                              <div class="text-danger mt-1">{{ $message }}</div>
                          @enderror
                </div>
              </div>

              <button type="submit" class="btn btn-danger me-2">Change Password</button>
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
    $('#settingForm').validate({
        rules: {
            first_name: { required: true, maxlength: 255 },
            last_name: { required: true, maxlength: 255 },
            email: { required: true, email: true },
        },
        messages: {
            first_name: "Please enter your first name",
            last_name: "Please enter your last name",
            email: "Please enter a valid email",
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

    // $('#changePasswordForm').validate({
    //     rules: {
    //         current_password: { required: true },
    //         new_password: { required: true, minlength: 6 },
    //         new_password_confirmation: { required: true, equalTo: '[name="new_password"]' }
    //     },
    //     messages: {
    //         current_password: "Please enter your Current password",
    //         new_password: "Password must be at least 6 characters",
    //         new_password_confirmation: "Password confirmation does not match"
    //     },
    //     errorElement: "span",
    //     errorClass: "text-danger d-block",
    //     highlight: function(element) {
    //         $(element).addClass("is-invalid");
    //     },
    //     unhighlight: function(element) {
    //         $(element).removeClass("is-invalid");
    //     }
    // });
});
</script>
@endpush
@endsection
