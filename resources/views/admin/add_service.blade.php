@extends('admin.layouts.layout')

@section('content')
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-md-12 grid-margin stretch-card">
                <?php
                  $service_name=$service_id="";  
                  if($service_detail)
                  {
                    $service_id=$service_detail->id;
                    $service_name=$service_detail->service;                   
                    
                  }
                ?>
                <div class="card">
                  <div class="card-body">
                    <a href="{{route('admin.serviceList')}}" class="btn btn-outline-info btn-fw" style="float: right;">Service List</a>
                    <h4 class="card-title">Service Management</h4>
                    <p class="card-description"> Add / Update Service  </p>
                    <form class="forms-sample" method="post" action="{{route('admin.addService')}}" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                      <div class="row">
                        <div class="form-group col-md-6">
                          <input type="hidden" name="id" value="{{$service_id}}">
                          <label for="exampleInputUsername1">Service Name</label>
                          <input required type="text" class="form-control form-control-sm" placeholder="Enter Service Name" aria-label="Servicename" name="service" value="{{$service_name}}">
                          
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