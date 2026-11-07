<!-- Left Sidebar Menu -->
    <div class="fixed-sidebar-left">
      <ul class="nav navbar-nav side-nav nicescroll-bar">
        <li class="navigation-header">
          <span>Main</span> 
          <i class="zmdi zmdi-more"></i>
        </li>
        <li>
          <a href="{{ \Sugihart\DeviceManager\Support\Urls::page('/main/user_list') }}" data-toggle="collapse" data-target="#app_dr">
            <div class="pull-left"><i class="fa fa-users mr-20"></i>
              <span class="right-nav-text">Users </span>
            </div>
            <div class="clearfix"></div></a>
        </li>
        <li>
          <a href="{{ \Sugihart\DeviceManager\Support\Urls::page('/main/vendor') }}" data-toggle="collapse" data-target="#app_dr">
            <div class="pull-left"><i class="fa fa-users mr-20"></i>
              <span class="right-nav-text">Vendor </span>
            </div>
            <div class="clearfix"></div></a>
        </li>
        
        <li class="navigation-header">
          <span>Device</span> 
          <i class="zmdi zmdi-more"></i>
        </li>
        @foreach($vendor as $vd)
        <li>
          <a href="{{ \Sugihart\DeviceManager\Support\Urls::page('/main/device/'.$vd->id) }}" data-toggle="collapse" data-target="#app_dr">
            <div class="pull-left"><i class="zmdi zmdi-apps mr-20"></i>
              <span class="right-nav-text">{{ $vd->name }} </span>
            </div>
            <div class="clearfix"></div></a>
        </li>
        @endforeach

        <li class="navigation-header">
          <span>Utility</span> 
          <i class="zmdi zmdi-more"></i>
        </li>
        <li>
          <a href="{{ \Sugihart\DeviceManager\Support\Urls::page('/main/log_activity') }}" data-toggle="collapse" data-target="#app_dr">
            <div class="pull-left"><i class="fa  fa-eye mr-20"></i>
              <span class="right-nav-text">Log Activity </span>
            </div>
            <div class="clearfix"></div></a>
        </li>
        
      </ul>
    </div>
    <!-- /Left Sidebar Menu -->