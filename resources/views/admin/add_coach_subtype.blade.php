@extends('admin.layouts.layout')

@section('content')
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-md-12 grid-margin stretch-card">
                <?php
                  $subtype_name=$coach_type_id=$coach_subtype_id="";  
                  if($coach_subtype)
                  {
                    $coach_subtype_id=$coach_subtype->id;
                    $coach_type_id=$coach_subtype->coach_type_id;
                    $subtype_name=$coach_subtype->subtype_name;
                    
                  }
                ?>
                <div class="card">
                  <div class="card-body">
                    <a href="{{route('admin.coachTypeList')}}" class="btn btn-outline-info btn-fw" style="float: right;">Coach SubCategory List</a>
                    <h4 class="card-title">Coach SubCategory Management</h4>
                    <p class="card-description"> Add / Update Coach SubCategory  </p>
                    <form class="forms-sample" method="post" action="{{route('admin.addCoachSubType')}}" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                      <div class="row">
                      <div class="form-group col-md-6">
                          <input type="hidden" name="id" value="{{$coach_subtype_id}}">
                          <label for="exampleInputUsername1">Coach Category</label>
                          <select class="form-select form-select-sm" id="coach_type_id" name="coach_type_id">
                            @if($coach_type)
                            @foreach($coach_type as $types)
                              <option value="{{$types->id }}" {{$coach_type_id==$types->id?'selected':''}}>{{$types->type_name}}</option>
                            @endforeach
                            @endif
                            
                          </select>
                        </div> 
                        <div class="form-group col-md-6">
                          <label for="exampleInputUsername1">Coach SubCategory</label>
                          <input type="text" class="form-control form-control-sm" placeholder="Enter Coach SubCategory"  name="subtype_name" value="{{$subtype_name}}" required>
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