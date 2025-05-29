@extends('admin.layouts.layout')

@section('content')
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-md-12 grid-margin stretch-card">
                <?php
                  $category_name=$coaching_cat_id="";  
                  if($category)
                  {
                    $coaching_cat_id=$category->id;
                    $category_name=$category->category_name;
                    
                  }
                ?>
                <div class="card">
                  <div class="card-body">
                    <a href="{{route('admin.coachingCategoryList')}}" class="btn btn-outline-info btn-fw" style="float: right;">Coaching Category List</a>
                    <h4 class="card-title">Coaching Category Management</h4>
                    <p class="card-description"> Add / Update Coaching Category </p>
                    <form class="forms-sample" method="post" action="{{route('admin.addCoachingCategory')}}" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                      <div class="row">
                        <div class="form-group col-md-6">
                          <input type="hidden" name="id" value="{{$coaching_cat_id}}">
                          <label for="exampleInputUsername1">Category Name</label>
                          <input type="text" class="form-control form-control-sm" placeholder="Enter category name"  name="category_name" value="{{$category_name}}" required>
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