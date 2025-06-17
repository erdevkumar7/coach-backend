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
                  
                  ?>

   <div class="container mt-4">
     <a href="{{ route('admin.viewCoach', ['id' => $coach_detail->coach_id]) }}" class="btn btn-outline-info btn-sm btn-fw" style="float: right;">View Coach</a>
                   <div class="row">
                    <div class="col-md-12">
                <div class="card">
                   <div class="card-body">
                      <h5 class="card-title">Coach Details</h5>
                          <div class="row">
                            <div class="form-group col-md-6">
                            <input type="hidden" name="coach_id" value="@if(!empty($coach_detail)){{$coach_detail->coach_id}}@endif">
                              <label for="exampleInputUsername1"><strong>First Name : </strong>@if(!empty($coach_detail)){{$coach_detail->coach_first_name}}@endif </label>
                            </div>
                            <div class="form-group col-md-6">
                              <label for="exampleInputUsername1"><strong>Last Name : </strong>@if(!empty($coach_detail)){{$coach_detail->coach_last_name}}@endif</label>
                            </div>
                            <div class="form-group col-md-6">
                              <label for="exampleInputEmail1"><strong>Email address : </strong>@if(!empty($coach_detail)){{$coach_detail->coach_email}}@endif</label>
                            </div>

                             <div class="form-group col-md-6">
                              <label for="exampleInputEmail1"><strong>Contact Number : </strong>@if(!empty($coach_detail)){{$coach_detail->coach_contact_number}}@endif</label>
                            </div>

                            <div class="form-group col-md-6">
                              <label for="exampleInputEmail1"><strong>Gender : </strong> @if(!empty($coach_detail->coach_gender)){{$coach_detail->coach_gender==1?'Male':($coach_detail->coach_gender==2?'Female':'Other')}}@endif</label>
                            </div>
                            <div class="form-group col-md-6">
                              <label for="exampleInputEmail1"><strong>Country : </strong> @if(!empty($coach_detail)){{$coach_detail->country_name}}@endif</label>
                            </div>
                            <div class="form-group col-md-6">
                           <label for="exampleInputEmail1"><strong>State : </strong>
                           @if(!empty($coach_detail) && $coach_detail->state_name)
                           {{ $coach_detail->state_name }}
                             @endif
                          </label>
                            </div>
                            <div class="form-group col-md-6">
                           <label for="exampleInputEmail1"><strong>City : </strong> @if(!empty($coach_detail) && $coach_detail->city_name)
                           {{ $coach_detail->city_name }}
                             @endif
                            </div>
                            <div class="form-group col-md-6">
                              <label for="exampleInputEmail1"><strong>Short Bio : </strong> @if(!empty($coach_detail) && $coach_detail->coach_short_bio)
                           {{ $coach_detail->coach_short_bio }}
                             @endif
                           </label>
                            </div>
                            <div class="form-group col-md-6">
                              <label for="exampleInputEmail1"><strong>Professional Title : </strong> 
                                @if(!empty($coach_detail) && $coach_detail->coach_professional_title)
                               {{ $coach_detail->coach_professional_title }}
                               @endif
                                </label>
                            </div>
                            <div class="form-group col-md-6">
                              <label for="exampleInputEmail1"><strong>Profile Image  : </strong></label>
                              @if(!empty($coach_detail->profile_image))
                              <img src="{{ url('public/uploads/profile_image/' . $coach_detail->profile_image)}}" style="max-width: 400px;max-height: 400px;">
                              @endif
                            </div>
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