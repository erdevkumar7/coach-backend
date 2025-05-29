@extends('admin.layouts.layout')

@section('content')
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-md-12 grid-margin stretch-card">
                <?php
                    $first_name=$last_name=$email=$gender=$user_id=$short_bio=$professional_title=$detailed_bio="";
                    
                    $country_name=$state_name=$city_name=$profile_image='';
                    if($user_detail)
                    {
                      $user_id=$user_detail->id;
                      $first_name=$user_detail->first_name;
                      $last_name=$user_detail->last_name;
                      $email=$user_detail->email;
                      $gender=$user_detail->gender;
                      $short_bio=$user_detail->short_bio;
                      $professional_title=$user_detail->professional_title;
                      $detailed_bio=$user_detail->detailed_bio;
                      $country_name=$user_detail->country_name;
                      $state_name=$user_detail->state_name;
                      $city_name=$user_detail->city_name;
                      $profile_image=$user_detail->profile_image;
                    }
                    $video_link=$experience=$coaching_category=$delivery_mode=$free_trial_session=$is_volunteered_coach="";
                    $volunteer_coaching=$website_link=$objective=$coach_type=$coach_subtype=$type_name=$subtype_name=$mode_name=$category_name="";
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
                      $type_name=$profession->type_name;
                      $subtype_name=$profession->subtype_name;
                      $category_name=$profession->category_name;
                      $mode_name=$profession->mode_name;
                    }
                  ?>
                <div class="card">
                  <div class="card-body">
                      <a href="{{route('admin.coachList')}}" class="btn btn-outline-info btn-fw" style="float: right;">Coach List</a>
                      <h4 class="card-title">Coach Management</h4>
                      <!--p class="card-description"> Add / Update Blog  </p-->

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                      <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Basic Profile</button>
                      </li>
                      <li class="nav-item" role="presentation">
                        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false" {{$user_id==''?'disabled':''}}>Professional Profile</button>
                      </li>
                      <li class="nav-item" role="presentation">
                        <button class="nav-link" id="messages-tab" data-bs-toggle="tab" data-bs-target="#messages" type="button" role="tab" aria-controls="messages" aria-selected="false">Enquiry</button>
                      </li>
                      <li class="nav-item" role="presentation">
                        <button class="nav-link" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button" role="tab" aria-controls="settings" aria-selected="false">Subscription</button>
                      </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                      <div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab">
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
                              <label for="exampleInputEmail1"><strong>Coach Type : </strong>{{$type_name}}</label>
                              
                            </div>
                            <div class="form-group col-md-6">
                              <label for="exampleInputEmail1"><strong>Coach SubType : </strong>{{$subtype_name}}</label>
                              
                            </div>
                            <div class="form-group col-md-6">
                              <label for="exampleInputEmail1"><strong>Gender : </strong> {{$gender==1?'Male':($gender==2?'Female':'Other')}}</label>
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
                              <label for="exampleInputEmail1"><strong>Coaching Category : </strong>{{$category_name}}</label>
                            </div>
                            
                            <div class="form-group col-md-6">
                              <label for="exampleInputEmail1"><strong>Delivery Mode : </strong>{{$mode_name}}</label>
                            </div>
                            <div class="form-group col-md-6">
                              <label for="exampleInputEmail1"><strong>Service Offered : </strong>{{$service->service_names}}</label>
                              
                            </div>
                            <div class="form-group col-md-6">
                              <label for="exampleInputEmail1"><strong>Language : </strong>{{$language->language_names}}</label>
                            </div>
                            <div class="form-group col-md-6">
                              <label for="exampleInputEmail1"><strong>Free Trial Session : </strong>{{$free_trial_session==1?'Yes':'No'}}</label>
                            </div>
                            <div class="form-group col-md-6">
                              <label for="exampleInputEmail1"><strong>Is Volunteered Coach  : </strong>{{$is_volunteered_coach==1?'Yes':'No'}}</label>
                            </div>
                            @if($is_volunteered_coach==1)
                            <div class="form-group col-md-6" id="vol_coach">
                              <label for="exampleInputEmail1"><strong>Area of volunteer coaching session  : </strong>{{$volunteer_coaching}}</label>
                            </div>
                            @endif
                            <div class="form-group col-md-6">
                              <label for="exampleInputEmail1"><strong>Profile Image  : </strong></label>
                              @if(!empty($profile_image))
                              <img src="{{ asset('public/uploads/profile_image/' . $profile_image)}}" style="max-width: 400px;max-height: 400px;">
                              @endif
                              
                            </div>
                          </div>
                      </div>
                      
                      <!--Coach Professional Profile-->
                      <div class="tab-pane" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                          <div class="row">
                            <div class="form-group col-md-6">
                              <label for="exampleInputUsername1"><strong>Experiance(In year)  : </strong>{{$experience}} Year</label>
                            </div>
                            
                            <div class="form-group col-md-6">
                              <label for="exampleInputUsername1"><strong>Price($)  : </strong>$ {{$price}}</label>
                            </div>
                            <div class="form-group col-md-6">
                              <label for="exampleInputEmail1"><strong>Video Introduction  : </strong><a href="{{$video_link}}" target="_blank">{{$video_link}}</a></label>
                            </div>
                            <div class="form-group col-md-6">
                              <label for="exampleInputEmail1"><strong>Website  : </strong><a href="{{$website_link}}" target="_blank">{{$website_link}}</a></label>
                            </div>
                            <div class="form-group col-md-6">
                              <label for="exampleInputEmail1"><strong>Objective of Coaching/Learning  : </strong>{{$objective}}</label>
                            </div>
                            <div class="form-group col-md-6">
                              <label for="exampleInputEmail1"><strong>Detailed Bio  : </strong>{{$detailed_bio}}</label>
                            </div>
                          </div>
                          <div id="documentContainer">
                            @php $i=1; @endphp  
                            @if($document)
                            @foreach($document as $documents)
                            <div class="row document-group">
                              <div class="form-group col-md-5">
                                <label ><strong>Document  : </strong>
                                  @if(!empty($documents->document_file))
                                      <a href="{{ asset('/public/uploads/documents/' . $documents->document_file) }}" target="_blank">{{ $documents->original_name }}</a>
                                  @endif
                                </label>
                              </div>
                              <div class="form-group col-md-5">
                                <label><strong>Document Type  : </strong> {{ $documents->document_type == 1 ? 'Certificate' : ($documents->document_type == 1?'CV':'Brochure') }}</label>
                              </div>
                            </div>
                            @php $i++; @endphp
                            @endforeach
                            @endif
                          </div>
                      </div>
                      
                      <div class="tab-pane" id="messages" role="tabpanel" aria-labelledby="messages-tab">
                        <div class="table-responsive">
                          <table class="table table-striped" id="example">
                            <thead>
                              <tr>
                                <th> Sr no </th>
                                <th> First name </th>
                                <th> Last name </th>
                                <th> Email </th>
                                <th> Enquiry </th>
                              </tr>
                            </thead>
                            <tbody>
                              
                            </tbody>
                          </table>
                        </div>
                      </div>

                      <div class="tab-pane" id="settings" role="tabpanel" aria-labelledby="settings-tab">
                        <div class="table-responsive">
                          <table class="table table-striped" id="example">
                            <thead>
                              <tr>
                                <th> Sr no </th>
                                <th> Plan name </th>
                                <th> Amount </th>
                                <th> Plan Duration </th>
                                <th> Start Date </th>
                                <th> End Date</th>
                              </tr>
                            </thead>
                            <tbody>
                              
                            </tbody>
                          </table>
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