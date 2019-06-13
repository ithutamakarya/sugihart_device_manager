<!-- Top Menu Items -->
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="mobile-only-brand pull-left">
        <div class="nav-header pull-left">
          <div class="logo-wrap">
            <a href="index.html">
              <img class="brand-img" src="{{asset('/images/ic_aegis.jpeg')}}" alt="brand" style="width: 30px;height: 30px" />
              <span class="brand-text">AEGISLITE</span>
            </a>
          </div>
        </div>  
        <a id="toggle_nav_btn" class="toggle-left-nav-btn inline-block ml-20 pull-left" href="javascript:void(0);"><i class="zmdi zmdi-menu"></i></a>
        <a id="toggle_mobile_search" data-toggle="collapse" data-target="#search_form" class="mobile-only-view" href="javascript:void(0);"><i class="zmdi zmdi-search"></i></a>
        <a id="toggle_mobile_nav" class="mobile-only-view" href="javascript:void(0);"><i class="zmdi zmdi-more"></i></a>
        <!-- <form id="search_form" role="search" class="top-nav-search collapse pull-left">
          <div class="input-group">
            <input type="text" name="example-input1-group2" class="form-control" placeholder="Search">
            <span class="input-group-btn">
            <button type="button" class="btn  btn-default"  data-target="#search_form" data-toggle="collapse" aria-label="Close" aria-expanded="true"><i class="zmdi zmdi-search"></i></button>
            </span>
          </div>
        </form> -->
      </div>
      <div id="mobile_only_nav" class="mobile-only-nav pull-right">
        <ul class="nav navbar-right top-nav pull-right">
          
          <li class="dropdown auth-drp">
            <a href="#" class="dropdown-toggle pr-0" data-toggle="dropdown">
              <?php if(Auth::user()->image){ ?><!-- 
                        <img src="{{ asset('storage/'.Auth::user()->image) }}" class="img-circle" alt="User Image"> -->
                        <img src="{{ asset('images/faces/face19.jpg') }}" class="user-auth-img img-circle" alt="User Image">
                      <?php  } else { ?>
                        <img src="{{ asset('images/faces/face19.jpg') }}" class="user-auth-img img-circle" alt="User Image">
                      <?php  } ?>
              <span class="user-online-status"></span>
            </a>
            <ul class="dropdown-menu user-auth-dropdown" data-dropdown-in="flipInX" data-dropdown-out="flipOutX">
              <li>
                <a href="javascript:;" data-toggle="modal" data-target="#modal-profile"><i class="zmdi zmdi-account"></i><span>Profile</span></a>
              </li>
              <!-- <li>
                <a href="#"><i class="zmdi zmdi-card"></i><span>my balance</span></a>
              </li>
              <li>
                <a href="inbox.html"><i class="zmdi zmdi-email"></i><span>Inbox</span></a>
              </li>
              <li>
                <a href="#"><i class="zmdi zmdi-settings"></i><span>Settings</span></a>
              </li> -->
              <li class="divider"></li>
              <!-- <li class="sub-menu show-on-hover">
                <a href="#" class="dropdown-toggle pr-0 level-2-drp"><i class="zmdi zmdi-check text-success"></i> available</a>
                <ul class="dropdown-menu open-left-side">
                  <li>
                    <a href="#"><i class="zmdi zmdi-check text-success"></i><span>available</span></a>
                  </li>
                  <li>
                    <a href="#"><i class="zmdi zmdi-circle-o text-warning"></i><span>busy</span></a>
                  </li>
                  <li>
                    <a href="#"><i class="zmdi zmdi-minus-circle-outline text-danger"></i><span>offline</span></a>
                  </li>
                </ul> 
              </li> -->
              <li class="divider"></li>
              <li>
                <a href="/logout"><i class="zmdi zmdi-power"></i><span>Log Out</span></a>
                
              </li>
            </ul>
          </li>
        </ul>
      </div>  
    </nav>
    <!-- /Top Menu Items -->
    
<div class="modal modal-success fade" id="modal-profile">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Profile</h4>
      </div>
      <div class="modal-body">
        <form>
        {!! csrf_field() !!}
          <div class="form-group">
            <label>Name</label>
            <input required type="text" class="form-control" placeholder="Name" name="name_profile" readonly value="{{ Auth::user()->name }}">
          </div>
          <div class="form-group">
            <label>Email</label>
            <input required type="text" class="form-control" placeholder="Email" name="email_profile" readonly value="{{ Auth::user()->email }}">
          </div>
          <div class="form-group">
            <label>Password</label>
            <input required type="password" class="form-control" placeholder="password" name="password_profile" readonly value="tergokil">
          </div>
          <div class="form-group">
            <label>Phone</label>
            <input required type="number" class="form-control" placeholder="Phone" name="phone_profile" readonly value="{{ Auth::user()->phone }}">
          </div>
          <div class="form-group">
            <label>Address</label>
            <textarea required class="form-control" placeholder="Address" name="address_profile" readonly>{{ Auth::user()->address }}</textarea>
          </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
        
      </form>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


    