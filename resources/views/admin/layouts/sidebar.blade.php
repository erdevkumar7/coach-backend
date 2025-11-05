<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <li class="nav-item">
      <a class="nav-link" href="{{route('admin.dashboard')}}">
        <i class="icon-grid menu-icon"></i>
        <span class="menu-title">Dashboard</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
        <i class="icon-head menu-icon"></i>
        <span class="menu-title">User Management</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="ui-basic">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="{{route('admin.userList')}}">User List</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{route('admin.coachList')}}">Coach List</a></li>

        </ul>
      </div>
    </li>

        <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#booking" aria-expanded="false" aria-controls="charts">
        <i class="icon-watch menu-icon"></i>
        <span class="menu-title">Subscriptions</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="booking">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="{{ route('admin.coachBookingList') }}">Coach Subscriptions</a></li>
          <!-- <li class="nav-item"> <a class="nav-link" href="#">User Booking</a></li> -->
        </ul>
      </div>
    </li>

    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#coachManagement" aria-expanded="false" aria-controls="coachManagement">
        <i class="mdi mdi-account-tie menu-icon"></i>
        <span class="menu-title">Coaching Management</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="coachManagement">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="{{ route('admin.coachTypeList') }}">Coach Category</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('admin.coachSubTypeList') }}">Coach Subcategory</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('admin.coachingCategoryList') }}">Coaching Category</a></li>
        </ul>
      </div>
    </li>


    <li class="nav-item">
      <a class="nav-link" href="{{route('admin.subscriptionList')}}">
        <i class="bi bi-gem menu-icon"></i>
        <span class="menu-title">Subscription Plan List</span>
      </a>
    </li>  

   <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#form-elements" aria-expanded="false" aria-controls="form-elements">
        <i class="icon-columns menu-icon"></i>
        <span class="menu-title">Enquiry Managment</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="form-elements">
        <ul class="nav flex-column sub-menu">
         <li class="nav-item"> <a class="nav-link" href="{{route('admin.enquiryList')}}">Enquiry List</a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#tables" aria-expanded="false" aria-controls="tables">
        <i class="icon-grid-2 menu-icon"></i>
        <span class="menu-title">Review Managment</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="tables">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="{{route('admin.reviewlist')}}">Review </a></li>
        </ul>
      </div>
    </li>

      <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#masters" aria-expanded="false" aria-controls="masters">
        <i class="icon-columns menu-icon"></i>
        <span class="menu-title">Masters</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="masters">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="{{route('admin.blogList')}}">Blog List</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{route('admin.serviceList')}}">Service List</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{route('admin.languageList')}}">Language List</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{route('admin.deliveryModeList')}}">Delivery Mode List</a></li>
      
          <!-- <li class="nav-item"> <a class="nav-link" href="{{route('admin.enquiryList')}}">Enquiry List</a></li> -->
        </ul>
      </div>
    </li>

      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#home" aria-expanded="false" aria-controls="home">
          <i class="ti-home menu-icon"></i>
          <span class="menu-title">Home Page Setting</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="home">
          <ul class="nav flex-column sub-menu">
             <li class="nav-item">
              <a class="nav-link" href="{{ route('admin.manage', 'top') }}">Top Section</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('admin.manage', 'global_partners') }}">Global Partners Section</a>
            </li>  
           <li class="nav-item"> 
              <a class="nav-link" href="{{route('admin.globalPartnersList')}}">Global Partners Images</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('admin.manage', 'middle_one') }}">Middle Section 1</a>
            </li>           

             <li class="nav-item">
              <a class="nav-link" href="{{ route('admin.manage', 'middle_two') }}">Middle Section 2</a>
             </li> 

            <li class="nav-item">
              <a class="nav-link" href="{{ route('admin.manage', 'category') }}">Coach Category Section</a>
             </li> 
            <li class="nav-item">
              <a class="nav-link" href="{{ route('admin.manage', 'plan') }}">Plan Section</a>
            </li>     
            
              <li class="nav-item">
              <a class="nav-link" href="{{ route('admin.manage', 'corporate') }}">Corporate Section</a>
             </li>  
             
              <li class="nav-item">
              <a class="nav-link" href="{{ route('admin.manage', 'footer_one') }}">Footer Section 1</a>
             </li>

               <li class="nav-item">
              <a class="nav-link" href="{{ route('admin.manage', 'footer_two') }}">Footer Section 2</a>
             </li>

              <li class="nav-item">
              <a class="nav-link" href="{{ route('admin.socialmedia') }}">Social Media</a>
             </li>

          </ul>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="{{route('admin.contact')}}">
          <i class="bi bi-envelope menu-icon"></i>
          <span class="menu-title">Contact-Us Page Setting</span>
        </a>
      </li>

           <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#about" aria-expanded="false" aria-controls="about">
          <i class="ti-home menu-icon"></i>
          <span class="menu-title">About-us Page Setting</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="about">
          <ul class="nav flex-column sub-menu">
             <li class="nav-item">
              <a class="nav-link" href="{{ route('admin.about', 'about_top') }}">About Top Section</a>
            </li>
             <li class="nav-item">
              <a class="nav-link" href="{{ route('admin.about', 'jurney') }}">Journey Section</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('admin.about', 'team') }}">Team Section</a>
            </li> 
            <li class="nav-item">
              <a class="nav-link" href="{{ route('admin.teamMember') }}">Team Member</a>
            </li> 
          </ul>
        </div>
      </li>

    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#charts" aria-expanded="false" aria-controls="charts">
        <i class="icon-bar-graph menu-icon"></i>
        <span class="menu-title">Policy</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="charts">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="{{route('admin.policyList')}}">Policy List</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{route('admin.addPolicy')}}">Add Policy</a></li>
        </ul>
      </div>
    </li>


    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#support" aria-expanded="false" aria-controls="tables">
        <i class="mdi mdi-headset menu-icon"></i>
        <span class="menu-title">FAQs and Support</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="support">
        <ul class="nav flex-column sub-menu">
            <li class="nav-item"> <a class="nav-link" href="{{ route('admin.faqs.index') }}">FAQs Management</a></li>
            <!-- <li class="nav-item"><a class="nav-link" href="{{route('admin.askSupportList')}}">Recent Supports </a></li> -->
        </ul>
      </div>

    </li>
    <!-- <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#coachingReq" aria-expanded="false" aria-controls="tables">
        <i class="mdi mdi-clipboard-text menu-icon"></i>
        <span class="menu-title">Coaching Requests</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="coachingReq">
        <ul class="nav flex-column sub-menu">
            <li class="nav-item"> <a class="nav-link" href="{{ route('admin.coachingRequest.index') }}">Coaching Requests</a></li>
        </ul>
      </div>
    </li> -->
    <!--<li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#icons" aria-expanded="false" aria-controls="icons">
        <i class="icon-contract menu-icon"></i>
        <span class="menu-title">Icons</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="icons">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="pages/icons/mdi.html">Mdi icons</a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
        <i class="icon-head menu-icon"></i>
        <span class="menu-title">User Pages</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="auth">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="pages/samples/login.html"> Login </a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/samples/register.html"> Register </a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#error" aria-expanded="false" aria-controls="error">
        <i class="icon-ban menu-icon"></i>
        <span class="menu-title">Error pages</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="error">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="pages/samples/error-404.html"> 404 </a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/samples/error-500.html"> 500 </a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="../../../docs/documentation.html">
        <i class="icon-paper menu-icon"></i>
        <span class="menu-title">Documentation</span>
      </a>
    </li-->
          <li class="nav-item">
        <a class="nav-link" href="{{route('admin.newsletter')}}">
          <i class="bi bi-envelope-paper menu-icon"></i>
          <span class="menu-title">Newsletter</span>
        </a>
      </li>
  </ul>

  
</nav>
