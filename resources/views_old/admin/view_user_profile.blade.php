@extends('admin.layouts.layout')

@section('content')
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-md-12 grid-margin stretch-card">
                <?php
                    $first_name=$last_name=$email=$gender=$user_id="";
                    $country_name=$state_name=$city_name=$profile_image='';
                    if($user_detail)
                    {
                      $user_id=$user_detail->id;
                      $first_name=$user_detail->first_name;
                      $last_name=$user_detail->last_name;
                      $email=$user_detail->email;
                      $gender=$user_detail->gender;
                      $country_name=$user_detail->country_name;
                      $state_name=$user_detail->state_name;
                      $city_name=$user_detail->city_name;
                      $profile_image=$user_detail->profile_image;
                    }
                    
                  ?>
                <div class="card">
                  <div class="card-body">
                      <a href="{{route('admin.userList')}}" class="btn btn-outline-info btn-fw" style="float: right;">User List</a>
                      <h4 class="card-title">User Management</h4>
                      <!--p class="card-description"> Add / Update Blog  </p-->

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                      <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Basic Profile</button>
                      </li>
                      <li class="nav-item" role="presentation">
                        <button class="nav-link" id="messages-tab" data-bs-toggle="tab" data-bs-target="#messages" type="button" role="tab" aria-controls="messages" aria-selected="false">Enquiry</button>
                      </li>
                      <!--li class="nav-item" role="presentation">
                        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false" {{$user_id==''?'disabled':''}}>Professional Profile</button>
                      </li>
                      <li class="nav-item" role="presentation">
                        <button class="nav-link" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button" role="tab" aria-controls="settings" aria-selected="false">Subscription</button>
                      </li-->
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
                              <label for="exampleInputEmail1"><strong>Profile Image  : </strong></label>
                              @if(!empty($profile_image))
                              <img src="{{ asset('public/uploads/profile_image/' . $profile_image)}}" style="max-width: 400px;max-height: 400px;">
                              @endif
                              
                            </div>
                          </div>
                      </div>
                      
                      
                      <div class="tab-pane" id="profile" role="tabpanel" aria-labelledby="profile-tab">
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