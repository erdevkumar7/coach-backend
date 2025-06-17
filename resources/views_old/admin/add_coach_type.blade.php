@extends('admin.layouts.layout')

@section('content')
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-md-12 grid-margin stretch-card">
                <?php
                  $type_name=$coach_type_id="";  
                  if($coach_type)
                  {
                    $coach_type_id=$coach_type->id;
                    $type_name=$coach_type->type_name;
                    
                  }
                ?>
                <div class="card">
                  <div class="card-body">
                    <a href="{{route('admin.coachTypeList')}}" class="btn btn-outline-info btn-fw" style="float: right;">Coach Category List</a>
                    <h4 class="card-title">Coach Category Management</h4>
                    <p class="card-description"> Add / Update Coach Category  </p>
                    <form class="forms-sample" method="post" action="{{route('admin.addCoachType')}}" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                      <div class="row">
                        <div class="form-group col-md-6">
                          <input type="hidden" name="id" value="{{$coach_type_id}}">
                          <label for="exampleInputUsername1">Coach Category</label>
                          <input type="text" class="form-control form-control-sm" placeholder="Enter Coach Category"  name="type_name" value="{{$type_name}}" required>
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