<!-- Left Sidebar Menu -->
    <div class="fixed-sidebar-left">
      <ul class="nav navbar-nav side-nav nicescroll-bar">
        <li class="navigation-header">
          <span>Main</span> 
          <i class="zmdi zmdi-more"></i>
        </li>
        <li>
          <a href="{{ url('/') }}" data-toggle="collapse" data-target="#dashboard_dr"><div class="pull-left"><i class="zmdi zmdi-landscape mr-20"></i><span class="right-nav-text">Dashboard</span></div><div class="clearfix"></div></a>
          
        </li>

        <li>
          <a href="/user_list" data-toggle="collapse" data-target="#app_dr">
            <div class="pull-left"><i class="fa fa-users mr-20"></i>
              <span class="right-nav-text">Users </span>
            </div>
            <div class="clearfix"></div></a>
        </li>
        <li>
          <a href="/device" data-toggle="collapse" data-target="#app_dr">
            <div class="pull-left"><i class="zmdi zmdi-apps mr-20"></i>
              <span class="right-nav-text">Device </span>
            </div>
            <div class="clearfix"></div></a>
        </li>
        <li>
          <a href="/devicelocation_list" data-toggle="collapse" data-target="#app_dr">
            <div class="pull-left"><i class="fa fa-map-marker mr-20"></i>
              <span class="right-nav-text">Location </span>
            </div>
            <div class="clearfix"></div></a>
        </li>
        
      </ul>
    </div>
    <!-- /Left Sidebar Menu -->