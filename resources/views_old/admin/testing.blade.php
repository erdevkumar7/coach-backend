@extends('admin.layouts.layout')

@section('content')
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-md-12 grid-margin stretch-card">
                
                <div class="card">
                  <div class="card-body">

                  <div class="w3-bar w3-black">
                    <button class="w3-bar-item w3-button" onclick="openCity('London')">London</button>
                    <button class="w3-bar-item w3-button" onclick="openCity('Paris')">Paris</button>
                    <button class="w3-bar-item w3-button" onclick="openCity('Tokyo')">Tokyo</button>
                  </div>
                  <div id="London" class="city">
                    <h2>London</h2>
                    <p>London is the capital of England.</p>
                  </div>

                  <div id="Paris" class="city" style="display:none">
                    <h2>Paris</h2>
                    <p>Paris is the capital of France.</p>
                  </div>

                  <div id="Tokyo" class="city" style="display:none">
                    <h2>Tokyo</h2>
                    <p>Tokyo is the capital of Japan.</p>
                  </div>
                  
                    <a href="{{route('admin.blogList')}}" class="btn btn-outline-info btn-fw" style="float: right;">Blog List</a>
                    <h4 class="card-title">Blog Management</h4>
                    <p class="card-description"> Add / Update Blog  </p>
                    <form class="forms-sample" method="post" action="{{route('admin.addBlog')}}" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                      <div class="row">
                        <div class="form-group col-md-6">
                          <input type="hidden" name="id" value="">
                          <label for="exampleInputUsername1">Blog Name</label>
                          <input required type="text" class="form-control form-control-sm" placeholder="Enter Blog Name" aria-label="Blogname" name="blog_name" value="">
                        </div>                        
                        
                        <div class="form-group col-md-6">                          
                          <label for="exampleInputUsername1">Blog Name</label>
                          <input type="text" class="form-control form-control-sm" placeholder="Enter Blog Name" aria-label="Blogimage" name="blog_image" value="blank.jpg">
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
       <script>
        function openCity(cityName) {
          var i;
          var x = document.getElementsByClassName("city");
          for (i = 0; i < x.length; i++) {
            x[i].style.display = "none";
          }
          document.getElementById(cityName).style.display = "block";
        }
       </script>
        @endpush