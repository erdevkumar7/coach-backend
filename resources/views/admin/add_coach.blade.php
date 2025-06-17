@extends('admin.layouts.layout')

@section('content')
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-md-12 grid-margin stretch-card">
                <?php
                  $first_name=$last_name=$email=$contact_number=$gender=$user_id=$short_bio=$professional_title="";
                  $country_id=$state_id=$city_id=0;
                  if($user_detail)
                  {
                    $user_id=$user_detail->id;
                    $first_name=$user_detail->first_name;
                    $last_name=$user_detail->last_name;
                    $email=$user_detail->email;
                    $contact_number=$user_detail->contact_number;
                    $gender=$user_detail->gender;
                    $country_id=$user_detail->country_id;
                    $state_id=$user_detail->state_id;
                    $city_id=$user_detail->city_id;
                    $short_bio=$user_detail->short_bio;
                    $professional_title=$user_detail->professional_title;
                  }
                  $video_link=$experience=$coaching_category=$delivery_mode=$free_trial_session=$is_volunteered_coach="";
                  $volunteer_coaching=$website_link=$objective=$coach_type=$coach_subtype="";
                  $price=0;
                  if($profession)
                  {
                    $video_link=$profession->video_link;
                    $experience=$profession->experience;
                    $coaching_category=$profession->coaching_category;
                    $delivery_mode=$profession->delivery_mode;
                    $free_trial_session=$profession->free_trial_session;
                    $price=$profession->price;
                    $is_volunteered_coach=$profession->is_volunteered_coach;
                    $volunteer_coaching=$profession->volunteer_coaching;
                    $website_link=$profession->website_link;
                    $objective=$profession->objective;
                    $coach_type=$profession->coach_type;
                    $coach_subtype=$profession->coach_subtype;
                  }
                ?>
                <div class="card">
                  <div class="card-body">
                    <a href="{{route('admin.coachList')}}" class="btn btn-outline-info btn-fw" style="float: right;">Coach List</a>
                    <h4 class="card-title">Coach Management</h4>
                    <p class="card-description"> Add / Update coach  </p>
                    <form class="forms-sample" method="post" action="{{route('admin.addCoach')}}" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                      <div class="row">
                        <div class="form-group col-md-6">
                          <input type="hidden" name="user_id" value="{{$user_id}}">
                          <label for="exampleInputUsername1">First Name</label>
                          <input required type="text" class="form-control form-control-sm" placeholder="First Name" aria-label="Username" name="first_name" value="{{$first_name}}">
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputUsername1">Last Name</label>
                          <input required type="text" class="form-control form-control-sm" placeholder="Last Name" aria-label="Username" name="last_name" value="{{$last_name}}">
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputEmail1">Email address</label>
                          <input required type="email" class="form-control form-control-sm" id="exampleInputEmail1" placeholder="Email" name="email" value="{{$email}}">
                        </div>
                         <div class="form-group col-md-6">
                              <label for="exampleInputEmail1">Contact Number</label>
                              <input required type="number" class="form-control form-control-sm" id="exampleInputEmail1" placeholder="contact number" name="contact_number" value="{{$contact_number}}">
                            </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputEmail1">Password</label>
                          <input type="password" class="form-control form-control-sm" id="exampleInputEmail1" placeholder="Password" name="password">
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputEmail1">Coach Type</label>
                          <select class="form-select form-select-sm" id="coach_type" name="coach_type">
                            <option>Select Coach Type</option>  
                              @if($type)
                              @foreach($type as $types)
                                <option value="{{$types->id }}" {{$coach_type==$types->id?'selected':''}}>{{$types->type_name}}</option>
                              @endforeach
                              @endif
                          </select>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputEmail1">Coach SubType</label>
                          <select class="form-select form-select-sm" id="coach_subtype" name="coach_subtype">
                            @if($subtype)
                            @foreach($subtype as $subtypes)
                              <option value="{{$subtypes->id }}" {{$coach_subtype==$subtypes->id?'selected':''}}>{{$subtypes->subtype_name}}</option>
                            @endforeach
                            @endif
                          </select>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputEmail1">Gender</label>
                          <select required class="form-select form-select-sm" id="exampleFormControlSelect3" name="gender">
                            <option value="1" {{$gender==1?'selected':''}}>Male</option>
                            <option value="2" {{$gender==2?'selected':''}}>Female</option>
                            <option value="3" {{$gender==3?'selected':''}}>Other</option>
                          </select>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputEmail1">Country</label>
                          <select required class="form-select form-select-sm" id="country" name="country_id">
                            <option>Select Country</option>  
                            @if($country)
                            @foreach($country as $country)
                              <option value="{{$country->country_id }}" {{$country_id==$country->country_id?'selected':''}}>{{$country->country_name}}</option>
                            @endforeach
                            @endif
                            
                          </select>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputEmail1">State</label>
                          <select required class="form-select form-select-sm" id="state" name="state_id">
                            <option>Select State</option>
                            @if($state)
                            @foreach($state as $states)
                            <option value="{{$states->state_id }}" {{$state_id==$states->state_id?'selected':''}}>{{$states->state_name}}</option>
                            @endforeach
                            @endif
                          </select>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputEmail1">City</label>
                          <select required class="form-select form-select-sm" id="city" name="city_id">
                            <option  >Select City</option>
                            @if($city)
                            @foreach($city as $cities)
                            <option value="{{$cities->city_id }}" {{$city_id==$cities->city_id?'selected':''}}>{{$cities->city_name}}</option>
                            @endforeach
                            @endif
                          </select>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputEmail1">Short Bio</label>
                          <textarea required class="form-control form-control-sm" name="short_bio" maxlength="300" id="short_bio">{{$short_bio}}</textarea>
                          <small id="bioCounter">300 characters remaining</small>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputEmail1">Professional Title</label>
                          <input required type="text" class="form-control form-control-sm" id="exampleInputEmail1" placeholder="Professional Title" name="professional_title" value="{{$professional_title}}">
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputEmail1">Coaching Category</label>
                          <select required class="form-select form-select-sm" id="exampleFormControlSelect3" name="coaching_category">
                            @if($category)
                            @foreach($category as $categ)
                            <option value="{{$categ->id}}" {{$coaching_category==$categ->id?'selected':''}}>{{$categ->category_name}}</option>
                            @endforeach
                            @endif
                          </select>
                        </div>
                        
                        <div class="form-group col-md-6">
                          <label for="exampleInputEmail1">Delivery Mode</label>
                          <select required class="form-select form-select-sm" id="exampleFormControlSelect3" name="delivery_mode">
                            @if($mode)
                            @foreach($mode as $modes)
                            <option value="{{$modes->id}}" {{$delivery_mode==$modes->id?'selected':''}}>{{$modes->mode_name}}</option>
                            @endforeach
                            @endif
                          </select>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputEmail1">Service Offered</label>
                          <select required class="js-example-basic-multiple w-100" multiple="multiple" name="service_offered[]">
                            @if($service)
                            @foreach($service as $services)
                              <option value="{{$services->id}}" {{ in_array($services->id, $selectedServiceIds) ? 'selected' : '' }}>{{$services->service}}</option>
                            @endforeach
                            @endif
                            
                          </select>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputEmail1">Language</label>
                          <select required class="js-example-basic-multiple w-100" multiple="multiple" name="language[]">
                            @if($language)
                            @foreach($language as $languages)
                              <option value="{{$languages->id}}" {{ in_array($languages->id, $selectedLanguageIds) ? 'selected' : '' }}>{{$languages->language}}</option>
                            @endforeach
                            @endif
                          </select>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputEmail1">Free Trial Session</label>
                          <select required class="form-select form-select-sm" id="exampleFormControlSelect3" name="free_trial_session">
                            <option value="1" {{$free_trial_session==1?'selected':''}}>Yes</option>
                            <option value="2" {{$free_trial_session==2?'selected':''}}>No</option>
                          </select>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputEmail1">Is Volunteered Coach</label>
                          <select  class="form-select form-select-sm" id="exampleFormControlSelect3" name="is_volunteered_coach">
                            <option value="1" {{$is_volunteered_coach==1?'selected':''}}>Yes</option>
                            <option value="2" {{$is_volunteered_coach==2?'selected':''}}>No</option>
                          </select>
                        </div>
                        <div class="form-group col-md-6" id="vol_coach">
                          <label for="exampleInputEmail1">Area of volunteer coaching session</label>
                          <input  type="text" class="form-control form-control-sm" id="exampleInputEmail1" placeholder="Area of volunteer coaching session" name="volunteer_coaching" value="{{$volunteer_coaching}}">
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputEmail1">Profile Image</label>
                          <input  type="file" class="form-control form-control-sm" id="exampleInputEmail1" name="profile_image" accept="image/png, image/gif, image/jpeg">
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

            $('#coach_type').change(function () {
              var sid = this.value;
              $.ajax({
                url: "{{url('/admin/getsubType')}}",
                type: "POST",
                datatype: "json",
                data: {
                  coach_type_id: sid,
                  '_token':'{{csrf_token()}}'
                },
                success: function(result) {
                  console.log(result);
                  $('#coach_subtype').html('<option value="">Select SubType</option>');
                  $.each(result.city, function(key, value) {
                    $('#coach_subtype').append('<option value="' +value.id+ '">' +value.subtype_name+ '</option>')
                  });
                },
                errror: function(xhr) {
                    console.log(xhr.responseText);
                  }
              });
            });
          });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const bio = document.getElementById('short_bio');
                const counter = document.getElementById('bioCounter');
                const max = 300;

                function updateCounter() {
                    const remaining = max - bio.value.length;
                    counter.textContent = `${remaining} characters remaining`;
                }

                bio.addEventListener('input', updateCounter);
                updateCounter(); // initial update
            });
        </script>
        <script>
          document.addEventListener('DOMContentLoaded', function () {
              const select = document.querySelector('select[name="is_volunteered_coach"]');
              const volCoachDiv = document.getElementById('vol_coach');

              function toggleVolCoach() {
                  if (select.value === '1') {
                      volCoachDiv.style.display = 'block';
                  } else {
                      volCoachDiv.style.display = 'none';
                  }
              }

              // Run on page load
              toggleVolCoach();

              // Run when the select changes
              select.addEventListener('change', toggleVolCoach);
          });
      </script>

        
        @endpush