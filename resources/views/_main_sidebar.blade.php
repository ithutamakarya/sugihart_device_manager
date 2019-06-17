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
          <a href="{{ url('/admin/user_list') }}" data-toggle="collapse" data-target="#app_dr">
            <div class="pull-left"><i class="fa fa-users mr-20"></i>
              <span class="right-nav-text">Users </span>
            </div>
            <div class="clearfix"></div></a>
        </li>
        <li>
          <a href="javascript:void(0);" data-toggle="collapse" data-target="#media"><div class="pull-left"><i class="fa fa-wpforms mr-20"></i><span class="right-nav-text">Media</span></div><div class="pull-right"><i class="zmdi zmdi-caret-down"></i></div><div class="clearfix"></div></a>
          <ul id="media" class="collapse collapse-level-1">
            <li>
              <a href="{{ url('/admin/group_media_list') }}">Group Media</a>
            </li>
            <li>
              <a href="{{ url('/admin/sub_group_media_list') }}">Sub Group Media</a>
            </li>
            <li>
              <a href="{{ url('/admin/media_list') }}">Media</a>
            </li>
          </ul>
        </li>

        <li>
          <a href="{{ url('/admin/category_list') }}" data-toggle="collapse" data-target="#app_dr">
            <div class="pull-left"><i class="zmdi zmdi-apps mr-20"></i>
              <span class="right-nav-text">Categories </span>
            </div>
            <div class="clearfix"></div></a>
        </li>

        <li>
          <a href="{{ url('/admin/sub_category_list') }}" data-toggle="collapse" data-target="#app_dr">
            <div class="pull-left"><i class="zmdi zmdi-apps mr-20"></i>
              <span class="right-nav-text">Sub Categories </span>
            </div>
            <div class="clearfix"></div></a>
        </li>

        <li>
          <a href="{{ url('/admin/competitor_list') }}" data-toggle="collapse" data-target="#app_dr">
            <div class="pull-left"><i class="fa fa-building-o mr-20"></i>
              <span class="right-nav-text">Competitor </span>
            </div>
            <div class="clearfix"></div></a>
        </li>

        <li>
          <a href="{{ url('/admin/keyword_list') }}" data-toggle="collapse" data-target="#app_dr">
            <div class="pull-left"><i class="fa fa-key mr-20"></i>
              <span class="right-nav-text">Keywords </span>
            </div>
            <div class="clearfix"></div></a>
        </li>

        <li>
          <a href="{{ url('/admin/log_activity') }}" data-toggle="collapse" data-target="#app_dr">
            <div class="pull-left"><i class="fa  fa-eye mr-20"></i>
              <span class="right-nav-text">Log Activity </span>
            </div>
            <div class="clearfix"></div></a>
        </li>
        
      </ul>
    </div>
    <!-- /Left Sidebar Menu -->