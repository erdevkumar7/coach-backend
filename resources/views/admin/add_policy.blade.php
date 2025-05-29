@extends('admin.layouts.layout')

@section('content')
<style>
  .ck-editor__editable {
    min-height: 300px !important; /* Or whatever height you want */
  }
</style>
  <?php
  
    $policy_id=$policy_content=$policy_type=$policy_name='';
    if($policies)
    {
      $policy_id=$policies->id;
      $policy_content=$policies->policy_content;
      $policy_type=$policies->policy_type;
      $policy_name=$policies->policy_name;
    }
  ?>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <a href="{{route('admin.policyList')}}" class="btn btn-outline-info btn-fw" style="float: right;">Policy List</a>
                    <h4 class="subscription-title">Information Management</h4>
                    <p class="subscription-description"> Add / Update Policy  </p>
                    <p></p>
                    <form class="forms-sample" method="post" action="{{route('admin.addPolicy')}}" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                      <div class="row">
                        <div class="form-group col-md-6">
                          <label for="exampleInputplan_name1">Policy Name</label>
                          <input type="hidden" name="policy_id" value="{{$policy_id}}">
                          <input type="text" class="form-control form-control-sm" id="policyname" placeholder="Policy Name" name="policy_name" value="{{$policy_name}}">
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputplan_name1">Policy Type</label>
                          <select required class="form-select form-select-sm" name="policy_type" id="exampleInputDurationUnit1">
                            <option value="1" {{$policy_type==1?'selected':''}}>Privacy Policy</option>
                            <option value="2" {{$policy_type==2?'selected':''}}>Terms & Conditions</option>
                            <option value="3" {{$policy_type==3?'selected':''}}>About Us</option>
                            <option value="4" {{$policy_type==4?'selected':''}}>FAQ</option>
                          </select>
                        </div>
                        <div class="form-group col-md-12">
                          <label for="video-introduction">Plan Content</label>
                          <textarea  class="form-control form-control-sm" id="video-introduction" name="policy_content" rows="1" placeholder="Enter policy content here..." >{{$policy_content}}</textarea>
                        </div>                        
                      </div>
                                            
                      <input type="hidden" name="user_time" value="" id="user_timezone">
                      <button type="submit" class="btn btn-primary me-2">Submit</button>
                    </form>
                    <span style="font-size: 12px;color: #d82828;">* Note:- If Policy already exist then this will update the previous policy content .</span>
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