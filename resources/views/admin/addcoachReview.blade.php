@extends('admin.layouts.layout')
@section('content')
<style>
  .ck-editor__editable { min-height: 300px !important; }
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
               <a href="{{route('admin.coachReview')}}" class="btn btn-outline-info btn-fw" style="float: right;">Coach Review List</a>
            <h4 class="card-title">{{ isset($CoachReview->id) ? 'Edit Coach Review' : 'Add Coach Review' }}</h4>

            <form id="coachreviewForm" method="post" action="{{ route('admin.addcoachReview', $CoachReview->id ?? '') }}">
              @csrf

              <div class="form-group col-md-12">
                <label>Title</label>
                <input required type="text" class="form-control form-control-sm" placeholder="Enter Title" name="title" value="{{ old('title', $CoachReview->title ?? '') }}">
                @error('title') <span class="text-danger">{{ $message }}</span> @enderror
              </div>        

              <div class="form-group col-md-12">
                <label>Description</label>
                <textarea class="form-control form-control-sm" name="description" placeholder="Enter description...">{{ old('description', $CoachReview->description ?? '') }}</textarea>
                @error('description') <span class="text-danger">{{ $message }}</span> @enderror
              </div>

              <div class="form-group col-md-12">
                  <label>Rating (1 to 5)</label>
                  <select required name="rating" class="form-control form-control-sm">
                      <option value="">Select Rating</option>

                      @for ($i = 1; $i <= 5; $i++)
                          <option value="{{ $i }}" 
                              {{ old('rating', $CoachReview->rating ?? '') == $i ? 'selected' : '' }}>
                              {{ $i }} Star{{ $i > 1 ? 's' : '' }}
                          </option>
                      @endfor
                  </select>

                  @error('rating')
                      <span class="text-danger">{{ $message }}</span>
                  @enderror
              </div>

                    <!-- <div class="form-group col-md-12">
                    <label>Rating (1 to 5)</label>
                    <select required name="rating" class="form-control form-control-sm">
                        <option value="">Select Rating</option>

                        <option value="1" {{ old('rating', $CoachReview->rating ?? '') == 1 ? 'selected' : '' }}>⭐ 1 Star</option>
                        <option value="2" {{ old('rating', $CoachReview->rating ?? '') == 2 ? 'selected' : '' }}>⭐⭐ 2 Stars</option>
                        <option value="3" {{ old('rating', $CoachReview->rating ?? '') == 3 ? 'selected' : '' }}>⭐⭐⭐ 3 Stars</option>
                        <option value="4" {{ old('rating', $CoachReview->rating ?? '') == 4 ? 'selected' : '' }}>⭐⭐⭐⭐ 4 Stars</option>
                        <option value="5" {{ old('rating', $CoachReview->rating ?? '') == 5 ? 'selected' : '' }}>⭐⭐⭐⭐⭐ 5 Stars</option>
                    </select>

                    @error('rating')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div> -->


              <div class="form-group col-md-12">
                <label>Coach</label>
                <select class="form-control form-control-sm" name="coach_id" required>
                  <option value="">Select Coach</option>
                  @foreach($coaches as $coach)
                    <option value="{{ $coach->id }}" {{ (old('coach_id', $CoachReview->coach_id ?? '') == $coach->id) ? 'selected' : '' }}>{{ $coach->first_name }} {{ $coach->last_name }}</option>
                  @endforeach
                </select>
                @error('coach_id') <span class="text-danger">{{ $message }}</span> @enderror
              </div>

              <div class="form-group col-md-12">
                <label>Designation</label>
                <input required type="text" class="form-control form-control-sm" placeholder="Enter Designation" name="designation" value="{{ old('designation', $CoachReview->designation ?? '') }}">
                @error('designation') <span class="text-danger">{{ $message }}</span> @enderror
              </div>

              <button type="submit" class="btn btn-primary me-2">{{ isset($CoachReview->id) ? 'Update' : 'Submit' }}</button>
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
           let isEdit = @json(isset($CoachReview->id));
        $('#coachreviewForm').validate({
          ignore: [],
          rules: {
            title: { required: true, maxlength: 25 },         
            description: { required: true, maxlength: 255 },
            rating: { required: true, number: true, min: 1, max: 5 },
            coach_id: { required: true },
            designation: { required: true, maxlength: 255 },
          },
          messages: {
            title: { required: "Please enter the Title", maxlength: "Title cannot exceed 25 characters" },
            description: { required: "Please enter the Description", maxlength: "Description cannot exceed 255 characters" },
            rating: { required: "Please enter the Rating", number: "Please enter a valid number", min: "Rating must be at least 1", max: "Rating cannot exceed 5" },
            coach_id: { required: "Please select a Coach" },
            designation: { required: "Please enter the Designation", maxlength: "Designation cannot exceed 255 characters" },
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
