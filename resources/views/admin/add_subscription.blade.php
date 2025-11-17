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
                          <textarea class="form-control form-control-sm" name="plan_content" rows="4" placeholder="Enter plan description here..." >{{$plan_content}}</textarea>
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
        

                    <div class="row">
                      <div class="form-group col-md-12">
                        <label>Plan Features</label>
                        <div id="feature-wrapper">
                          @php $count = 1; @endphp
                          @if(isset($features) && count($features) > 0)
                            @foreach($features as $f)
                              <div class="feature-item mb-3">
                                <label class="fw-bold">Feature {{ $count++ }}</label>
                                <div class="d-flex align-items-start">
                                  <textarea name="features[]" class="form-control form-control-sm me-2" rows="2" placeholder="Enter feature description...">{{ $f->feature_text }}</textarea>
                                  <button type="button" class="btn btn-danger btn-sm remove-feature ms-2">X</button>
                                </div>
                              </div>
                            @endforeach
                          @else
                            <div class="feature-item mb-3">
                              <label class="fw-bold">Feature 1</label>
                              <div class="d-flex align-items-start">
                                <textarea name="features[]" class="form-control form-control-sm me-2" rows="2" placeholder="Enter feature description..."></textarea>
                                <button type="button" class="btn btn-danger btn-sm remove-feature ms-2">X</button>
                              </div>
                            </div>
                          @endif
                        </div>
                        <button type="button" class="btn btn-success btn-sm" id="add-feature">+ Add More</button>
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


              <script>
                document.getElementById('add-feature').addEventListener('click', function() {
                    const wrapper = document.getElementById('feature-wrapper');
                    const featureCount = wrapper.querySelectorAll('.feature-item').length + 1;
                    
                    const featureDiv = document.createElement('div');
                    featureDiv.classList.add('feature-item', 'mb-3');
                    featureDiv.innerHTML = `
                      <label class="fw-bold">Feature ${featureCount}</label>
                      <div class="d-flex align-items-start">
                        <textarea name="features[]" class="form-control form-control-sm me-2" rows="2" placeholder="Enter feature description..."></textarea>
                        <button type="button" class="btn btn-danger btn-sm remove-feature ms-2">X</button>
                      </div>
                    `;
                    wrapper.appendChild(featureDiv);
                    updateFeatureLabels();
                });

                // Remove feature
                document.addEventListener('click', function(e) {
                    if (e.target.classList.contains('remove-feature')) {
                        e.target.closest('.feature-item').remove();
                        updateFeatureLabels();
                    }
                });

                // Update feature numbers
                function updateFeatureLabels() {
                    const features = document.querySelectorAll('#feature-wrapper .feature-item label');
                    features.forEach((label, index) => {
                        label.textContent = 'Feature ' + (index + 1);
                    });
                }
              </script>


        @endpush