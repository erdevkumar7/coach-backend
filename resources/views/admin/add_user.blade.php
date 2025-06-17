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
                  }
                ?>
                <div class="card">
                  <div class="card-body">
                    <a href="{{route('admin.userList')}}" class="btn btn-outline-info btn-fw" style="float: right;">User List</a>
                    <h4 class="card-title">User Management</h4>
                    <p class="card-description"> Add / Update user  </p>
                    <form class="forms-sample" method="post" action="{{route('admin.addUser')}}" enctype="multipart/form-data">
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
                          <input required type="number" class="form-control form-control-sm" id="exampleInputEmail1" placeholder="Contact Number" name="contact_number" value="{{$contact_number}}">
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputEmail1">Password</label>
                          <input type="password" class="form-control form-control-sm" id="exampleInputEmail1" placeholder="Password" name="password">
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
                            <option value="">Select Country</option>  
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
                            <option>Select City</option>
                            @if($city)
                            @foreach($city as $cities)
                            <option value="{{$cities->city_id }}" {{$city_id==$cities->city_id?'selected':''}}>{{$cities->city_name}}</option>
                            @endforeach
                            @endif
                          </select>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputEmail1">Profile Image</label>
                          <input type="file" class="form-control form-control-sm" id="exampleInputEmail1" name="profile_image" accept="image/png, image/gif, image/jpeg">
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
          });
        </script>
        @endpush