@extends('admin.layouts.layout')

@section('content')
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-md-12 grid-margin stretch-card">
                <?php
                  $language_name=$language_id="";  
                  if($language_detail)
                  {
                    $language_id=$language_detail->id;
                    $language_name=$language_detail->language;
                    
                  }
                ?>
                <div class="card">
                  <div class="card-body">
                    <a href="{{route('admin.languageList')}}" class="btn btn-outline-info btn-fw" style="float: right;">Language List</a>
                    <h4 class="card-title">Language Management</h4>
                    <p class="card-description"> Add / Update Language  </p>
                    <form class="forms-sample" method="post" action="{{route('admin.addLanguage')}}" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                      <div class="row">
                        <div class="form-group col-md-6">
                          <input type="hidden" name="id" value="{{$language_id}}">
                          <label for="exampleInputUsername1">Language Name</label>
                          <input required type="text" class="form-control form-control-sm" placeholder="Enter Language Name" aria-label="Languagename" name="language" value="{{$language_name}}">
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
       
        @endpush