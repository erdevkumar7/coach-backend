<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <li class="nav-item">
      <a class="nav-link" href="{{route('admin.dashboard')}}">
        <i class="icon-grid menu-icon"></i>
        <span class="menu-title">Dashboard</span>
      </a>
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
          <li class="nav-item"> <a class="nav-link" href="{{route('admin.coachTypeList')}}">Coach Category List</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{route('admin.subscriptionList')}}">Subscription Plan List</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{route('admin.coachSubTypeList')}}">Coach SubCategory List</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{route('admin.coachingCategoryList')}}">Coaching Category List</a></li>
          <!-- <li class="nav-item"> <a class="nav-link" href="{{route('admin.enquiryList')}}">Enquiry List</a></li> -->
        </ul>
      </div>
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
  </ul>
</nav>