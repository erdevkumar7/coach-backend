@extends('admin.layouts.layout')

@section('content')
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-md-12 grid-margin stretch-card">
                <?php
                  $first_name=$last_name=$email=$contact_number=$gender=$user_id="";
                  $country_id=$state_id=$city_id=0;
                //   if($user_detail)
                //   {
                //     $user_id=$user_detail->id;
                //     $first_name=$user_detail->first_name;
                //     $last_name=$user_detail->last_name;
                //     $email=$user_detail->email;
                //     $contact_number=$user_detail->contact_number;
                //     $gender=$user_detail->gender;
                //     $country_id=$user_detail->country_id;
                //     $state_id=$user_detail->state_id;
                //     $city_id=$user_detail->city_id;
                //   }
                ?>
                <div class="card">
                  <div class="card-body">
                    <a href="{{route('admin.userList')}}" class="btn btn-outline-info btn-fw" style="float: right;">User List</a>
                    <h4 class="card-title">User Management</h4>
                    <p class="card-description"> Add / Update user  </p>
                <form class="forms-sample" method="post" action="{{ route('admin.addServicePackage') }}" enctype="multipart/form-data">
    @csrf
    <div class="row">

        <!-- Service Title -->
        <div class="form-group col-md-6">
            <label>Service Title</label>
            <input type="text" name="title" class="form-control" placeholder="e.g., Confidence Jumpstart Session" value="{{ old('title') }}">
        </div>

        <!-- Short Description -->
        <div class="form-group col-md-6">
            <label>Short Description</label>
            <input type="text" name="short_description" class="form-control" maxlength="200" placeholder="Snapshot descriptions" value="{{ old('short_description') }}">
        </div>

        <!-- Coaching Category -->
        <div class="form-group col-md-6">
            <label>Coaching Category</label>
            <input type="text" name="coaching_category" class="form-control" value="{{ old('coaching_category') }}">
        </div>

        <!-- Detail Description -->
        <div class="form-group col-md-6">
            <label>Detail Descriptions</label>
            <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
        </div>

        <!-- Service Focus -->
        <div class="form-group col-md-6">
            <label>Service Focus</label>
            <input type="text" name="focus" class="form-control" placeholder="e.g., Confidence, Goal Clarity" value="{{ old('focus') }}">
        </div>

        <!-- Coaching Type -->
        <div class="form-group col-md-6">
            <label>Coaching Type</label>
            <select name="coaching_type" class="form-control">
                <option value="">Select</option>
                <option value="1-on-1">1-on-1 Coaching</option>
                <option value="group">Group Session</option>
                <option value="workshop">Workshop / Master Class</option>
                <option value="program">Coaching Program</option>
                <option value="webinar">Webinar / Live Talk</option>
                <option value="dropin">Drop-In / On Demand</option>
                <option value="corporate">Corporate / Team Training</option>
                <option value="trial">Trial / Discovery Session</option>
                <option value="free">Free / Pro-Bono</option>
            </select>
        </div>

        <!-- Delivery Mode -->
        <div class="form-group col-md-6">
            <label>Delivery Mode</label>
            <input type="text" name="delivery_mode" class="form-control" placeholder="e.g., Online / Offline / Hybrid" value="{{ old('delivery_mode') }}">
        </div>

        <!-- No. of Sessions -->
        <div class="form-group col-md-3">
            <label>Number of Sessions</label>
            <input type="number" name="session_count" class="form-control" value="{{ old('session_count') }}">
        </div>

        <!-- Session Duration -->
        <div class="form-group col-md-3">
            <label>Session Duration</label>
            <input type="text" name="session_duration" class="form-control" placeholder="e.g., 60 Min/Session" value="{{ old('session_duration') }}">
        </div>

        <!-- Targeted Audience -->
        <div class="form-group col-md-6">
            <label>Targeted Audience</label>
            <input type="text" name="target_audience" class="form-control" placeholder="e.g., First-timers or job seekers" value="{{ old('target_audience') }}">
        </div>

        <!-- Price -->
        <div class="form-group col-md-3">
            <label>Total Price</label>
            <input type="text" name="price" class="form-control" value="{{ old('price') }}">
        </div>

        <!-- Currency -->
        <div class="form-group col-md-3">
            <label>Currency</label>
            <select name="currency" class="form-control">
                <option value="USD">USD</option>
                <option value="INR">INR</option>
                <option value="EUR">EUR</option>
            </select>
        </div>

        <!-- Booking Slot and Validity -->
        <div class="form-group col-md-6">
            <label>Slots Available For Booking</label>
            <input type="date" name="booking_slot" class="form-control" value="{{ old('booking_slot') }}">
        </div>

        <div class="form-group col-md-6">
            <label>Booking Window (Date Range)</label>
            <input type="text" name="booking_window" id="date_range" class="form-control" value="{{ old('booking_window') }}">
        </div>

        <!-- Cancellation Policy -->
        <div class="form-group col-md-6">
            <label>Cancellation Policy</label>
            <select name="cancellation_policy" class="form-control">
                <option value="flexible">Flexible – Full Refund if canceled ≥24 hrs</option>
                <option value="moderate">Moderate – 50% refund if canceled ≥24 hrs</option>
                <option value="strict">Strict – No refund if canceled <48 hrs</option>
            </select>
        </div>

        <!-- Rescheduling Policy -->
        <div class="form-group col-md-6">
            <label>Rescheduling Policy</label>
            <input type="text" name="rescheduling_policy" class="form-control" value="{{ old('rescheduling_policy', 'Free one time reschedule allowed') }}">
        </div>

        <!-- Media Upload -->
        <div class="form-group col-md-6">
            <label>Media Upload</label>
            <input type="file" name="media_file" class="form-control" accept="image/*,video/*">
        </div>

        <!-- Submit -->
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary">Submit Package</button>
        </div>
    </div>
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
        <script>
          document.addEventListener("DOMContentLoaded", function () {
              const userTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
              document.getElementById("user_timezone").value = userTimezone;
          });
          $(document).ready(function () {
            $(document).on('change', '#country', function () {
              var cid = this.value;   //let cid = $(this).val(); we cal also write this.
              $.ajax({
                url: "{{url('/admin/getstate')}}",
                type: "POST",
                datatype: "json",
                data: {
                  country_id: cid,
                  '_token':'{{csrf_token()}}'
                },
                success: function(result) {
                  $('#state').html('<option value="">Select State</option>');
                  $.each(result.state, function(key, value) {
                    $('#state').append('<option value="' +value.state_id+ '">' +value.state_name+ '</option>');
                  });
                },
                errror: function(xhr) {
                    console.log(xhr.responseText);
                  }
                });
            });

            $('#state').change(function () {
              var sid = this.value;
              $.ajax({
                url: "{{url('/admin/getcity')}}",
                type: "POST",
                datatype: "json",
                data: {
                  state_id: sid,
                  '_token':'{{csrf_token()}}'
                },
                success: function(result) {
                  console.log(result);
                  $('#city').html('<option value="">Select City</option>');
                  $.each(result.city, function(key, value) {
                    $('#city').append('<option value="' +value.city_id+ '">' +value.city_name+ '</option>')
                  });
                },
                errror: function(xhr) {
                    console.log(xhr.responseText);
                  }
              });
            });
          });
        </script>
        @endpush
