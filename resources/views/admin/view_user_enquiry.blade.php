@extends('admin.layouts.layout')

@section('content')
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-md-12 grid-margin stretch-card">
                <?php
                    $first_name=$last_name=$email=$contact_number=$gender=$user_id=$short_bio=$professional_title=$detailed_bio="";
                    $country_name=$state_name=$city_name=$profile_image='';
                    if($user_detail)
                    {
                      $user_id=$user_detail->user_id;
                      $first_name=$user_detail->user_first_name;
                      $last_name=$user_detail->user_last_name;
                      $email=$user_detail->user_email;
                      $contact_number=$user_detail->user_contact_number;
                      $gender=$user_detail->user_gender;
                      $short_bio=$user_detail->user_short_bio;
                      $professional_title=$user_detail->user_professional_title;
                      $detailed_bio=$user_detail->user_detailed_bio;
                      $country_name=$user_detail->country_name;
                      $state_name=$user_detail->state_name;
                      $city_name=$user_detail->city_name;
                      $profile_image=$user_detail->user_profile_image;
                    }
                  ?>

   <div class="container mt-4">
     <a href="{{ route('admin.viewUser', ['id' => $user_id]) }}" class="btn btn-outline-info btn-sm btn-fw" style="float: right;">View User </a>
                   <div class="row">
                      <div class="col-md-12">
                       <div class="card">
                         <div class="card-body">
                           <h5 class="card-title">Users Details</h5>
                            <div class="row">
                            <div class="form-group col-md-6">
                              <input type="hidden" name="user_id" value="{{$user_id}}">
                              <label for="exampleInputUsername1"><strong>First Name : </strong> {{$first_name}}</label>
                            </div>
                            <div class="form-group col-md-6">
                              <label for="exampleInputUsername1"><strong>Last Name : </strong>{{$last_name}}</label>
                            </div>
                            <div class="form-group col-md-6">
                              <label for="exampleInputEmail1"><strong>Email address : </strong>{{$email}}</label>
                            </div>

                             <div class="form-group col-md-6">
                              <label for="exampleInputEmail1"><strong>Contact Number : </strong>{{$contact_number}}</label>
                            </div>
                            <div class="form-group col-md-6">
                              <label for="exampleInputEmail1"><strong>Gender : </strong> {{$gender==1?'Male':($gender==2?'Female':'')}}</label>
                            </div>
                            <div class="form-group col-md-6">
                              <label for="exampleInputEmail1"><strong>Country : </strong> {{$country_name}}</label>
                              
                            </div>
                            <div class="form-group col-md-6">
                              <label for="exampleInputEmail1"><strong>State : </strong> {{$state_name}}</label>
                              
                            </div>
                            <div class="form-group col-md-6">
                              <label for="exampleInputEmail1"><strong>City : </strong> {{$city_name}}</label>
                              
                            </div>
                            <div class="form-group col-md-6">
                              <label for="exampleInputEmail1"><strong>Short Bio : </strong> {{$short_bio}}</label>
                            </div>
                            <div class="form-group col-md-6">
                              <label for="exampleInputEmail1"><strong>Professional Title : </strong> {{$professional_title}}</label>
                            </div>
                          
                            <div class="form-group col-md-6">
                              <label for="exampleInputEmail1"><strong>Profile Image  : </strong></label>
                              @if(!empty($profile_image))
                              <img src="{{ url('public/uploads/profile_image/' . $profile_image)}}" style="max-width: 400px;max-height: 400px;">
                              @endif
                              
                            </div>
                          </div>
                     </div>
                </div>
           </div>


    <!-- Card 2 -->
                <div class="col-md-12 mt-3">
                  <div class="card">
                    <div class="card-body">
                       <h5 class="card-title">Enquiry Details</h5>
                         <div class="row">
                            <div class="form-group col-md-6">
                              <input type="hidden" name="user_id" value="{{$user_id}}">
                              <label for="exampleInputUsername1"><strong>Enquiry Title : </strong> @if(!empty($enquiry_detail)){{$enquiry_detail->enquiry_title}}@endif</label>
                            </div>
                            <div class="form-group col-md-6">
                              <label for="exampleInputUsername1"><strong>Enquiry Detail : </strong>@if(!empty($enquiry_detail)){{$enquiry_detail->enquiry_detail}}@endif</label>
                            </div>
                            </div>
                          </div>
                       </div>
                      </div>
                    </div>
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
            var triggerTabList = [].slice.call(document.querySelectorAll('#myTab a'))
            triggerTabList.forEach(function (triggerEl) {
              var tabTrigger = new bootstrap.Tab(triggerEl)

              triggerEl.addEventListener('click', function (event) {
                event.preventDefault()
                tabTrigger.show()
              })
            })
          </script>
        @endpush