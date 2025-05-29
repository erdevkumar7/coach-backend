@extends('admin.layouts.layout')

@section('content')
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-md-12 grid-margin stretch-card">
              
              <?php
                  $plan_name=$plan_amount=$duration_unit=$plan_duration=$plan_content=$subscription_plan_id="";                  
                  if($subscription_detail)
                  {
                    $subscription_plan_id=$subscription_detail->id;                    
                    $plan_name=$subscription_detail->plan_name;
                    $plan_amount=$subscription_detail->plan_amount;
                    $duration_unit=$subscription_detail->duration_unit;
                    $plan_duration=$subscription_detail->plan_duration;
                    $plan_content=$subscription_detail->plan_content;
                  }
                ?>


                <div class="card">
                  <div class="card-body">
                    <a href="{{route('admin.subscriptionList')}}" class="btn btn-outline-info btn-fw" style="float: right;">Subscription Plan List</a>
                    <h4 class="subscription-title">Subscription Plan Management</h4>
                    <p class="subscription-description"> Add / Update Subscription  </p>
                    <p></p>
                    <form class="forms-sample" method="post" action="{{route('admin.addSubscription')}}" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                      <div class="row">
                        <input type="hidden" name="id" value="{{$subscription_plan_id}}">
                        <div class="form-group col-md-6">
                          <label for="exampleInputplan_name1">Subscription Plan Name</label>
                          <input required type="text" class="form-control form-control-sm" placeholder="Enter Subscription Plan Name" name="plan_name" value="{{$plan_name}}">
                          
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputSplan_amounte1">Plan Amount($)</label>
                          <input required type="text" class="form-control form-control-sm" placeholder="price($)" maxlength="5" name="plan_amount" oninput="this.value = this.value.replace(/[^0-9]/g, '')" value="{{$plan_amount}}">
                        </div>
                        <div class="form-group col-md-12">
                          <label for="video-introduction">Plan Content</label>
                          <textarea class="form-control form-control-sm" id="video-introduction" name="plan_content" rows="1" placeholder="Enter plan description here..." >{{$plan_content}}</textarea>
                        </div>                        
                      </div>
                      <div class="row">                        
                        <div class="form-group col-md-6">
                          <label for="exampleInputSplanDuration1">Plan Duration</label>
                          <input required type="text" class="form-control form-control-sm" placeholder="Enter Plan Duration" oninput="this.value = this.value.replace(/[^0-9]/g, '')" maxlength="3" name="plan_duration" value="{{$plan_duration}}">
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputDurationUnit1">Duration Unit</label>
                          <select required class="form-control form-control-sm" name="duration_unit" id="exampleInputDurationUnit1">
                            <option value="1" {{$duration_unit==1?'selected':''}}>Day</option>
                            <option value="2" {{$duration_unit==2?'selected':''}}>Month</option>
                            <option value="3" {{$duration_unit==3?'selected':''}}>Year</option>
                          </select>
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
  
          <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
          <script>
              ClassicEditor
                  .create(document.querySelector('#video-introduction'))
                  .catch(error => {
                      console.error(error);
                  });
          </script>
    
        @endpush