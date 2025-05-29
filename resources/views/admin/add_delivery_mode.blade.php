@extends('admin.layouts.layout')

@section('content')
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-md-12 grid-margin stretch-card">
                <?php
                  $mode_name=$mode_id="";  
                  if($delivery)
                  {
                    $mode_id=$delivery->id;
                    $mode_name=$delivery->mode_name;
                    
                  }
                ?>
                <div class="card">
                  <div class="card-body">
                    <a href="{{route('admin.deliveryModeList')}}" class="btn btn-outline-info btn-fw" style="float: right;">Delivery Mode List</a>
                    <h4 class="card-title">Delivery Mode Management</h4>
                    <p class="card-description"> Add / Update Delivery Mode </p>
                    <form class="forms-sample" method="post" action="{{route('admin.addDeliveryMode')}}" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                      <div class="row">
                        <div class="form-group col-md-6">
                          <input type="hidden" name="id" value="{{$mode_id}}">
                          <label for="exampleInputUsername1">Mode Name</label>
                          <input type="text" class="form-control form-control-sm" placeholder="Enter Mode name"  name="mode_name" value="{{$mode_name}}" required>
                        </div>                        
                      </div>
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