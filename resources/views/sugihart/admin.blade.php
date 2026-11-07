@if ($screen === 'admin')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <title>Sugihart Device Manager</title>
  <meta name="description" content="Elmer is a Dashboard & Admin Site Responsive Template by hencework." />
  <meta name="keywords" content="admin, admin dashboard, admin template, cms, crm, Elmer Admin, Elmeradmin, premium admin templates, responsive admin, sass, panel, software, ui, visualization, web app, application" />
  <meta name="author" content="hencework"/>
  
  <!-- Favicon -->
  <link rel="shortcut icon" href="{{\App\Http\Controllers\Sugihart\DeviceManagerController::assetUrl('elmer/dist/img/user.png') }}" />
  
  <!-- Data table CSS -->
  <link href="{{ \App\Http\Controllers\Sugihart\DeviceManagerController::assetUrl('elmer/vendors/bower_components/datatables/media/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css"/>
  
  <!-- select2 CSS -->
  <link href="{{ \App\Http\Controllers\Sugihart\DeviceManagerController::assetUrl('elmer/vendors/bower_components/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>

  <!-- bootstrap-select CSS -->
  <link href="{{ \App\Http\Controllers\Sugihart\DeviceManagerController::assetUrl('elmer/vendors/bower_components/bootstrap-select/dist/css/bootstrap-select.min.css') }}" rel="stylesheet" type="text/css"/>

  <link href="{{ \App\Http\Controllers\Sugihart\DeviceManagerController::assetUrl('elmer/vendors/bower_components/jquery-toast-plugin/dist/jquery.toast.min.css') }}" rel="stylesheet" type="text/css">
    
  
  <!-- Custom CSS -->
  <link href="{{ \App\Http\Controllers\Sugihart\DeviceManagerController::assetUrl('elmer/dist/css/style.css') }}" rel="stylesheet" type="text/css">


  <!-- Sweet Alert -->
  <link rel="stylesheet" href="{{\App\Http\Controllers\Sugihart\DeviceManagerController::assetUrl('css/sweetalert.css')}}">
  @if ($page === 'home')

@endif

@if ($page === 'user')

@endif

@if ($page === 'vendor')

@endif

@if ($page === 'device')

@endif

@if ($page === 'log')

@endif

    <style type="text/css">
      .invisible_data{
        display: none;
      }
      /*   .img-flag{
        width: 30px !important;
        height: 15px !important;

      }*/
      /*//image_preview*/

    .image-preview-input {
        position: relative;
      overflow: hidden;
      margin: 0px;    
        color: #333;
        background-color: #fff;
        border-color: #ccc;    
    }
    .image-preview-input input[type=file] {
      position: absolute;
      top: 0;
      right: 0;
      margin: 0;
      padding: 0;
      font-size: 20px;
      cursor: pointer;
      opacity: 0;
      filter: alpha(opacity=0);
    }
    .image-preview-input-title {
        margin-left:2px;
    }
    .image_preview{
        width: 250px;
        height: 200px;
        display: none;
    }

    /*//image_preview 2*/

    .image-preview-input2 {
        position: relative;
      overflow: hidden;
      margin: 0px;    
        color: #333;
        background-color: #fff;
        border-color: #ccc;    
    }
    .image-preview-input2 input[type=file] {
      position: absolute;
      top: 0;
      right: 0;
      margin: 0;
      padding: 0;
      font-size: 20px;
      cursor: pointer;
      opacity: 0;
      filter: alpha(opacity=0);
    }
    .image-preview-input-title2 {
        margin-left:2px;
    }
    .image_preview2{
        width: 250px;
        height: 200px;
        display: none;
    }
    .close {
      position: absolute;
      right: 22px;
      top: 10px;
      width: 32px;
      height: 32px;
      opacity: 0.8;
      background-color: black;
    }
    .close:hover {
      opacity: 1;
    }
    .close:before, .close:after {
      position: absolute;
      left: 15px;
      content: ' ';
      height: 33px;
      width: 2px;
      background-color: #ffffff;
    }
    .close:before {
      transform: rotate(45deg);
    }
    .close:after {
      transform: rotate(-45deg);
    }

    </style>
</head>

<body>
  <!-- Preloader -->
  <div class="preloader-it">
    <div class="la-anim-1"></div>
  </div>
  <!-- /Preloader -->
    <div class="wrapper  theme-1-active pimary-color-blue">
    
    <!-- Top Menu Items -->
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="mobile-only-brand pull-left">
        <div class="nav-header pull-left">
          <div class="logo-wrap">
            <a href="{{ route('sdm.home') }}">
              <img class="brand-img" src="https://sugihartdigital.id/wp-content/uploads/2018/12/atas.png" alt="brand" style="width: 80%;" />
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
              <?php if(auth('sdm')->user()->image){ ?><!-- 
                        <img src="{{ \App\Http\Controllers\Sugihart\DeviceManagerController::assetUrl('storage/'.auth('sdm')->user()->image) }}" class="img-circle" alt="User Image"> -->
                <img src="{{ \App\Http\Controllers\Sugihart\DeviceManagerController::assetUrl('elmer/dist/img/user.png') }}" class="user-auth-img img-circle" alt="User Image">
              <?php  } else { ?>
                <img src="{{ \App\Http\Controllers\Sugihart\DeviceManagerController::assetUrl('elmer/dist/img/user.png') }}" class="user-auth-img img-circle" alt="User Image">
              <?php  } ?>
              <span class="user-online-status"></span>
            </a>
            <ul class="dropdown-menu user-auth-dropdown" data-dropdown-in="flipInX" data-dropdown-out="flipOutX">
              
              <li>
                <a href="{{ route('sdm.logout') }}"><i class="zmdi zmdi-power"></i><span>Log Out</span></a>
                
              </li>
            </ul>
          </li>
        </ul>
      </div>  
    </nav>
    <!-- /Top Menu Items -->


    <!-- sidebar -->
    <!-- Left Sidebar Menu -->
    <div class="fixed-sidebar-left">
      <ul class="nav navbar-nav side-nav nicescroll-bar">
        <li class="navigation-header">
          <span>Main</span> 
          <i class="zmdi zmdi-more"></i>
        </li>
        <li>
          <a href="{{ \App\Http\Controllers\Sugihart\DeviceManagerController::pageUrl('/main/user_list') }}" data-toggle="collapse" data-target="#app_dr">
            <div class="pull-left"><i class="fa fa-users mr-20"></i>
              <span class="right-nav-text">Users </span>
            </div>
            <div class="clearfix"></div></a>
        </li>
        <li>
          <a href="{{ \App\Http\Controllers\Sugihart\DeviceManagerController::pageUrl('/main/vendor') }}" data-toggle="collapse" data-target="#app_dr">
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
          <a href="{{ \App\Http\Controllers\Sugihart\DeviceManagerController::pageUrl('/main/device/'.$vd->id) }}" data-toggle="collapse" data-target="#app_dr">
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
          <a href="{{ \App\Http\Controllers\Sugihart\DeviceManagerController::pageUrl('/main/log_activity') }}" data-toggle="collapse" data-target="#app_dr">
            <div class="pull-left"><i class="fa  fa-eye mr-20"></i>
              <span class="right-nav-text">Log Activity </span>
            </div>
            <div class="clearfix"></div></a>
        </li>
        
      </ul>
    </div>
    <!-- /Left Sidebar Menu -->
    
        <!-- Main Content -->
    <div class="page-wrapper">
      
      @if ($errors->any())
        <div class="alert alert-danger" role="alert"><ul>
          @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul></div>
      @endif
      @if ($page === 'home')

<style type="text/css">
  .card {
      box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
      transition: 0.3s;
      min-width: 500px;
      border-radius: 5px;
      padding: 5px;
      min-height: 200px;
  }

  .card:hover {
      box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
  }

  .card img {
      border-radius: 5px 5px 0 0;
      width:100px;height: 100px
  }

  .card .container-card {
      padding: 2px 16px;
  }

  .list_check td{
    padding: 10px;
  }
</style>
<div class="container-fluid">
        
        <!-- Title -->
        <div class="row heading-bg">
          <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h5 class="txt-dark">Dashboard</h5>
          </div>
          <!-- Breadcrumb -->
          <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            
          </div>
          <!-- /Breadcrumb -->
        </div>
        <!-- /Title -->
        
        <!-- Row -->
        <div class="row">

          <div class="col-sm-12">
            
            <div class="panel panel-primary card-view">
              <div class="panel-heading">
                <div class="pull-left">
                  <h6 class="panel-title txt-light">GROUP</h6>
                </div>
                <div class="clearfix"></div>
              </div>
              <div class="panel-wrapper collapse in">
                <div class="panel-body">
                      
                </div>
              </div>
            </div>
          </div>
        </div>
 
        <!-- /Row -->
      </div>


@endif

@if ($page === 'user')

<div class="container-fluid">
        
        <!-- Title -->
        <div class="row heading-bg">
          <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h5 class="txt-dark">Data User</h5>
          </div>
          <!-- Breadcrumb -->
          <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            
          </div>
          <!-- /Breadcrumb -->
        </div>
        <!-- /Title -->
        
        <!-- Row -->
        <div class="row">
          <div class="col-sm-12">
            <div class="panel panel-default card-view">
              <div class="panel-heading">
                <div class="pull-left">
                  <h6 class="panel-title txt-dark">User</h6>
                </div>
                <div class="pull-right">
                  <a href="javascript:;" class="btn btn-primary" data-toggle="modal" data-target="#modal-success" id="add_data">
                    Add User
                  </a>
                </div>
                <div class="clearfix"></div>
              </div>
              <div class="panel-wrapper collapse in">
                <div class="panel-body">
                  <div class="table-wrap">
                    <div class="table-responsive">
                      <table id="datable_1" class="table table-hover display  pb-30" >
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th style="display: none;">Reg Id</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>

                        @foreach($users as $row => $p)
                        
                        <tr>
                          <td >{{ $row + 1 }}</td>            
                          <td id="{{ 'name'.$row }}" >{{$p->name}}</td>
                          <td id="{{ 'email'.$row }}" >{{$p->email}}</td>
                          <td style="display: none;">
                          <p id="{{ 'id'.$row }}" class="invisible_data">{{ $p->id }}</p></td>
                          <td >
                          <!-- /user/view/{{$p->id}} -->
                            <a href="javascript:void(0);" class="btn btn-success delete_data" data-url="{{ \App\Http\Controllers\Sugihart\DeviceManagerController::pageUrl('main/delete_user/'.$p->id) }}">Delete</a>
                            <a href="javascript:void(0);" data-id="{{ $row }}" class="btn btn-warning edit_data">Edit</a>
                          </td>
                        </tr>
                        @endforeach
                          
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>  
          </div>
        </div>
        <!-- /Row -->
        
 
      </div>

<div class="modal modal-success fade" id="modal-success">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Add User</h4>
      </div>
      <div class="modal-body">
        <form method="post" action="{{ \App\Http\Controllers\Sugihart\DeviceManagerController::pageUrl('main/create_user') }}" id="form-action">
        {!! csrf_field() !!}
          <input required type="hidden" name="id">
          <div class="form-group">
            <label>Name</label>
            <input required type="text" class="form-control" placeholder="Name" name="name">
          </div>
          <div class="form-group">
            <label>Email</label>
            <input required type="text" class="form-control" placeholder="Email" name="email">
          </div>
          <div class="form-group">
            <label>Password</label>
            <input required type="password" class="form-control" placeholder="password" name="password">
          </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary pull-left" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary saveBtn">Save changes</button>
      </form>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

@endif

@if ($page === 'vendor')

<div class="container-fluid">
        
        <!-- Title -->
        <div class="row heading-bg">
          <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h5 class="txt-dark">Data vendor </h5>
          </div>
          <!-- Breadcrumb -->
          <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            
          </div>
          <!-- /Breadcrumb -->
        </div>
        <!-- /Title -->
        
        <!-- Row -->
        <div class="row">
          <div class="col-sm-12">
            <div class="panel panel-default card-view">
              <div class="panel-heading">
                <div class="pull-left">
                  <h6 class="panel-title txt-dark">vendor</h6>
                </div>
                <div class="pull-right">
                  <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modal-success" id="add_data">
                    Add vendor
                  </a>
                </div>
                <div class="clearfix"></div>
              </div>
              <div class="panel-wrapper collapse in">
                <div class="panel-body">
                  <div class="table-wrap">
                    <div class="table-responsive">
                      <table id="datable_1" class="table table-hover display  pb-30" >
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Token</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>

                        @foreach($vendor as $row => $p)
                        
                        <tr>
                          <td >{{ $row + 1 }}</td>            
                          <td id="{{ 'name'.$row }}" >{{ $p->name }}</td>
                          <td id="{{ 'token'.$row }}" >{{ $p->token }}</td>
                          <td >
                            <div style="display: none;">
                              <p id="{{ 'id'.$row }}" class="invisible_data">{{ $p->id }}</p>
                            </div>
                          <!-- /vendor/view/{{$p->id}} -->
                            <a href="javascript:void(0);" class="btn btn-success delete_data" data-url="{{ \App\Http\Controllers\Sugihart\DeviceManagerController::pageUrl('main/delete_vendor/'.$p->id) }}">Delete</a>
                            <a href="javascript:void(0);" data-id="{{ $row }}" class="btn btn-warning edit_data">Edit</a>
                          </td>
                        </tr>
                        @endforeach
                          
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>  
          </div>
        </div>
        <!-- /Row -->
        
 
      </div>

<div class="modal modal-success fade" id="modal-success">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Add vendor</h4>
      </div>
      <div class="modal-body">
        <form method="post" action="{{ \App\Http\Controllers\Sugihart\DeviceManagerController::pageUrl('main/create_vendor') }}" id="form-action">
        {!! csrf_field() !!}
          <input required type="hidden" name="id">
          <div class="form-group">
            <label>Name</label>
            <input required type="text" class="form-control" placeholder="Name" name="name">
          </div>

          <div class="form-group">
            <label>Token</label>
            <input required type="text" class="form-control" placeholder="Token" name="token" readonly>
          </div>
          
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary pull-left" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary saveBtn">Save changes</button>
      </form>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

@endif

@if ($page === 'device')

<div class="container-fluid">
        
        <!-- Title -->
        <div class="p->id heading-bg">
          <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h5 class="txt-dark">Data Device {{ $vendor_name }}</h5>
          </div>
          <!-- Breadcrumb -->
          <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            
          </div>
          <!-- /Breadcrumb -->
        </div>
        <!-- /Title -->
        
        <!-- p->id -->
        <div class="p->id">
          <div class="col-sm-12">
            <div class="panel panel-default card-view">
              <div class="panel-heading">
                <div class="pull-left">
                  <h6 class="panel-title txt-dark">device</h6>
                </div>
                <div class="pull-right">
                  <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modal-success" id="add_data">
                    Add Device
                  </a>
                </div>
                <div class="clearfix"></div>
              </div>
              <div class="panel-wrapper collapse in">
                <div class="panel-body">
                  <div class="pills-struct mt-40">
                    <ul role="tablist" class="nav nav-pills" id="myTabs_6">
                      <li class="" role="presentation" class="active"><a aria-expanded="false" data-toggle="tab" role="tab" id="home_tab_6" href="#cctv_tab">CCTV</a></li>
                      <li role="presentation" ><a data-toggle="tab" id="profile_tab_6" role="tab" href="#kios_tab" aria-expanded="true">KIOS K</a></li>
                      
                    </ul>
                    <div class="tab-content" id="myTabContent_6">
                      <div id="cctv_tab" class="tab-pane fade" role="tabpanel">
                        <div class="table-wrap">
                          <div class="table-responsive">
                            <table id="datable_1" class="table table-hover display  pb-30" >
                              <thead>
                                <tr>
                                  <th>#</th>
                                  <th>Name</th>
                                  <th>URL</th>
                                  <th>Type</th>
                                  <th>IP Access</th>
                                  <th>Token</th>
                                  <th>Action</th>
                                </tr>
                              </thead>
                              <tbody>

                              @foreach($device_cctv as $row => $p)
                              
                              <tr>
                                <td >{{ $row + 1 }}</td>            
                                <td id="{{ 'name'.$p->id }}" >{{ $p->name }}</td>
                                <td id="{{ 'url_device'.$p->id }}" >{{ $p->url_device }}</td>
                                <td id="{{ 'type'.$p->id }}" >{{ $p->type }}</td>
                                <td id="{{ 'ip_access'.$p->id }}" >{{ $p->ip_access }}</td>
                                <td id="{{ 'token'.$p->id }}" >{{ $p->token }}</td>
                                <td >
                                  <div style="display: none;">
                                    <p id="{{ 'id'.$p->id }}" class="invisible_data">{{ $p->id }}</p>
                                  </div>
                                <!-- /device/view/{{$p->id}} -->
                                  @if($p->type == "cctv")
                                  <a href="javascript:void(0);" data-id="{{ $p->id }}" class="btn btn-primary create_sub">Create Sub</a>
                                  @endif
                                  <a href="javascript:void(0);" class="btn btn-success delete_data" data-url="{{ \App\Http\Controllers\Sugihart\DeviceManagerController::pageUrl('main/delete_device/'.$p->id.'/'.$vendor_id) }}">Delete</a>
                                  <a href="javascript:void(0);" data-id="{{ $p->id }}" class="btn btn-warning edit_data">Edit</a>
                                </td>
                              </tr>
                              @endforeach
                                
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                      <div id="kios_tab" class="tab-pane fade active in" role="tabpanel">
                        <div class="table-wrap">
                          <div class="table-responsive">
                            <table id="datable_1" class="table table-hover display  pb-30" >
                              <thead>
                                <tr>
                                  <th>#</th>
                                  <th>Name</th>
                                  <th>URL</th>
                                  <th>Type</th>
                                  <th>IP Access</th>
                                  <th>Token</th>
                                  <th>Action</th>
                                </tr>
                              </thead>
                              <tbody>

                              @foreach($device_kios as $row => $p)
                              
                              <tr>
                                <td >{{ $row + 1 }}</td>            
                                <td id="{{ 'name'.$p->id }}" >{{ $p->name }}</td>
                                <td id="{{ 'url_device'.$p->id }}" >{{ $p->url_device }}</td>
                                <td id="{{ 'type'.$p->id }}" >{{ $p->type }}</td>
                                <td id="{{ 'ip_access'.$p->id }}" >{{ $p->ip_access }}</td>
                                <td id="{{ 'token'.$p->id }}" >{{ $p->token }}</td>
                                <td >
                                  <div style="display: none;">
                                    <p id="{{ 'id'.$p->id }}" class="invisible_data">{{ $p->id }}</p>
                                  </div>
                                <!-- /device/view/{{$p->id}} -->
                                  @if($p->type == "cctv")
                                  <a href="javascript:void(0);" data-id="{{ $p->id }}" class="btn btn-primary create_sub">Create Sub</a>
                                  @endif
                                  <a href="javascript:void(0);" class="btn btn-success delete_data" data-url="{{ \App\Http\Controllers\Sugihart\DeviceManagerController::pageUrl('main/delete_device/'.$p->id.'/'.$vendor_id) }}">Delete</a>
                                  <a href="javascript:void(0);" data-id="{{ $p->id }}" class="btn btn-warning edit_data">Edit</a>
                                </td>
                              </tr>
                              @endforeach
                                
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                      
                    </div>
                  </div>

                 

                </div>
              </div>
            </div>  
          </div>
        </div>
        <!-- /p->id -->
        
 
      </div>

<div class="modal modal-success fade" id="modal-success">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Add device</h4>
      </div>
      <div class="modal-body">
        <form method="post" action="{{ \App\Http\Controllers\Sugihart\DeviceManagerController::pageUrl('main/create_device') }}" id="form-action">
        {!! csrf_field() !!}
          <input required type="hidden" name="id">
          <div class="form-group">
            <label>Name</label>
            <input required type="text" class="form-control" placeholder="Name" name="name">
          </div>
          <div class="form-group">
            <label>URL</label>
            <input required type="text" class="form-control" placeholder="URL" name="url_device">
          </div>
          <div class="form-group">
            <label>Type</label>
            <select class="form-control" name="type" id="type" required>
              <option value="">-- Select Type --</option>
              <option value="kios-k">Kios K</option>
              <option value="cctv">CCTV</option>
            </select>
          </div>
          <input type="hidden" name="vendor_id" value="{{ $vendor_id }}">
          <div class="form-group">
            <label>IP</label>
            <input required type="text" class="form-control" placeholder="IP Access" name="ip_access">
          </div>

          <div class="form-group">
            <label>Token</label>
            <input required type="text" class="form-control" placeholder="Token" name="token" readonly>
          </div>
          
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary pull-left" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary saveBtn">Save changes</button>
      </form>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

@endif

@if ($page === 'log')

<div class="container-fluid">
        
        <!-- Title -->
        <div class="row heading-bg">
          <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h5 class="txt-dark">Data Log</h5>
          </div>
          <!-- Breadcrumb -->
          <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            
          </div>
          <!-- /Breadcrumb -->
        </div>
        <!-- /Title -->
        
        <!-- Row -->
        <div class="row">
          <div class="col-sm-12">
            <div class="panel panel-default card-view">
              <div class="panel-heading">
                <div class="pull-left">
                  <h6 class="panel-title txt-dark">Log Activity</h6>
                </div>
                <div class="clearfix"></div>
              </div>
              <div class="panel-wrapper collapse in">
                <div class="panel-body">
                  <div class="table-wrap">
                    <div class="table-responsive">
                      <table id="datable_1" class="table table-hover display  pb-30" >
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>User / Device</th>
                            <th>Activity</th>
                            <th>IP Address</th>
                            <th>Recorded At</th>
                          </tr>
                        </thead>
                        <tbody>

                        @foreach($log_activity as $row => $p)
                        
                        <tr>
                          <td>{{ $row + 1 }}</td>            
                          <td>{{$p->user_device }}</td>           
                          <td>{{$p->activity }}</td>       
                          <td>{{$p->ip_address }}</td>     
                          <td>{{$p->created_at }}</td>
                        </tr>
                        @endforeach
                          
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>  
          </div>
        </div>
        <!-- /Row -->
        
 
      </div>


@endif


      
<!-- Footer -->
<footer class="footer container-fluid pl-30 pr-30">
	<div class="row">
		<div class="col-sm-12">
			<p>
				<strong>Copyright &copy; 2018 
					<a href="https://sugihartdigitalimaji.com">SugihartDigitalImaji</a>.
				</strong> 
				All rights reserved.
			</p>
		</div>
	</div>
</footer>

      <!-- /Footer -->
      
    </div>
        <!-- /Main Content -->

    </div>
    <!-- /#wrapper -->
  
  <!-- JavaScript -->
  
    <!-- jQuery -->
    <script src="{{ \App\Http\Controllers\Sugihart\DeviceManagerController::assetUrl('elmer/vendors/bower_components/jquery/dist/jquery.min.js') }}"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="{{ \App\Http\Controllers\Sugihart\DeviceManagerController::assetUrl('elmer/vendors/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    
  <!-- Data table JavaScript -->
  <script src="{{ \App\Http\Controllers\Sugihart\DeviceManagerController::assetUrl('elmer/vendors/bower_components/datatables/media/js/jquery.dataTables.min.js') }}"></script>
  <script src="{{ \App\Http\Controllers\Sugihart\DeviceManagerController::assetUrl('elmer/dist/js/dataTables-data.js') }}"></script>
  

  <!-- Slimscroll JavaScript -->
  <script src="{{ \App\Http\Controllers\Sugihart\DeviceManagerController::assetUrl('elmer/dist/js/jquery.slimscroll.js') }}"></script>
  
  <!-- Progressbar Animation JavaScript -->
  <script src="{{ \App\Http\Controllers\Sugihart\DeviceManagerController::assetUrl('elmer/vendors/bower_components/waypoints/lib/jquery.waypoints.min.js') }}"></script>
  <script src="{{ \App\Http\Controllers\Sugihart\DeviceManagerController::assetUrl('elmer/vendors/bower_components/jquery.counterup/jquery.counterup.min.js') }}"></script>
  
  <!-- Fancy Dropdown JS -->
  <script src="{{ \App\Http\Controllers\Sugihart\DeviceManagerController::assetUrl('elmer/dist/js/dropdown-bootstrap-extended.js') }}"></script>
  
  <!-- Sparkline JavaScript -->
  <script src="{{ \App\Http\Controllers\Sugihart\DeviceManagerController::assetUrl('elmer/vendors/jquery.sparkline/dist/jquery.sparkline.min.js') }}"></script>

    
  <!-- Moment JavaScript -->
  <script type="text/javascript" src="{{ \App\Http\Controllers\Sugihart\DeviceManagerController::assetUrl('elmer/vendors/bower_components/moment/min/moment-with-locales.min.js') }}"></script>
  
      
  <!-- Bootstrap Datetimepicker JavaScript -->
  <script type="text/javascript" src="{{ \App\Http\Controllers\Sugihart\DeviceManagerController::assetUrl('elmer/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') }}"></script>

  <!-- Owl JavaScript -->
  <script src="{{ \App\Http\Controllers\Sugihart\DeviceManagerController::assetUrl('elmer/vendors/bower_components/owl.carousel/dist/owl.carousel.min.js') }}"></script>
  
  <!-- Switchery JavaScript -->
  <script src="{{ \App\Http\Controllers\Sugihart\DeviceManagerController::assetUrl('elmer/vendors/bower_components/switchery/dist/switchery.min.js') }}"></script>
  
  <!-- EChartJS JavaScript -->
  <script src="{{ \App\Http\Controllers\Sugihart\DeviceManagerController::assetUrl('elmer/vendors/bower_components/echarts/dist/echarts-en.min.js') }}"></script>
  <script src="{{ \App\Http\Controllers\Sugihart\DeviceManagerController::assetUrl('elmer/vendors/echarts-liquidfill.min.js') }}"></script>
  

  <!-- Bootstrap Select JavaScript -->
  <script src="{{ \App\Http\Controllers\Sugihart\DeviceManagerController::assetUrl('elmer/vendors/bower_components/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>

  <!-- Select2 JavaScript -->
  <script src="{{ \App\Http\Controllers\Sugihart\DeviceManagerController::assetUrl('elmer/vendors/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

  <!-- Toast JavaScript -->
  <script src="{{ \App\Http\Controllers\Sugihart\DeviceManagerController::assetUrl('elmer/vendors/bower_components/jquery-toast-plugin/dist/jquery.toast.min.js') }}"></script>
  
  <!-- Init JavaScript -->
  <script src="{{ \App\Http\Controllers\Sugihart\DeviceManagerController::assetUrl('elmer/dist/js/init.js') }}"></script><!-- 
  <script src="{{ \App\Http\Controllers\Sugihart\DeviceManagerController::assetUrl('elmer/dist/js/dashboard-data.js') }}"></script> -->

<script src="{{\App\Http\Controllers\Sugihart\DeviceManagerController::assetUrl('js/sweetalert.min.js') }}"></script>

    <!-- Tinymce JavaScript -->
    <script src="{{\App\Http\Controllers\Sugihart\DeviceManagerController::assetUrl('elmer/vendors/bower_components/tinymce/tinymce.min.js') }}"></script>
    <!-- Tinymce JavaScript -->
    <script src="{{\App\Http\Controllers\Sugihart\DeviceManagerController::assetUrl('elmer/dist/js/tinymce-data.js') }}"></script>
      

  <script type="text/javascript">
    
/*****Load function start*****/
$(window).load(function(){

    /* Select2 Init*/
    $(".select2").select2();
    /* Bootstrap Select Init*/
    $('.selectpicker').selectpicker();



    //prevent a # target
    $(document).on('click', 'a[href="#"]', function(e) {
      e.preventDefault();
    });

    //Delete Function
    $(".delete_data").on("click",function(){
      var url = $(this).attr("data-url");

      swal({
        title: "Apakah Anda Yakin?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Iya",
        cancelButtonText: "Tidak",
        closeOnConfirm: false,
        closeOnCancel: false
      },
      function(isConfirm) {
        if (isConfirm) {
          var form = document.createElement('form');
          form.method = 'POST';
          form.action = url;
          var csrf = document.createElement('input');
          csrf.type = 'hidden'; csrf.name = '_token';
          csrf.value = {{ Illuminate\Support\Js::from(csrf_token()) }};
          form.appendChild(csrf); document.body.appendChild(form); form.submit();
          /*swal("Deleted!", "Your imaginary file has been deleted.", "success");*/
        } else {
          swal("Cancelled", "Data Anda Aman :)", "error");
        }
      });
    
    })

  @if (session()->has('sdm.message_alert'))
    window.setTimeout(function(){
      $.toast({
        heading: 'Information',
        text: '{{ session("sdm.message_alert") }}',
        position: 'bottom-left',
        loaderBg:'#f8b32d',
        icon: '{{ session('sdm.status_alert') }}',
        hideAfter: 3500, 
        stack: 1
      });
    }, 1000);
  @endif


});
  </script>

      @if ($page === 'home')

<script type="text/javascript">

    var n_pos = 0;

    var imgPosition = n_pos;  
    var p_multi = n_pos;

  $(function(){
    $("#image_preview").on("click",".close_img_multiple",function(){
      
      var data = $(this).attr("data-id");
      $("#mul_" + data).remove();
      $("#img_multiple_" + data).fadeOut();

    })
    
  });
  function preview_images() 
    {
         var total_file = document.getElementById("images").files.length;
         for(var i=0;i<total_file;i++)
         {

          $('#image_preview').append("<div class='col-md-3' id='img_multiple_" + imgPosition + "' style='margin-bottom:10px'><a href='javascript:;' class='close close_img_multiple' data-id='" + imgPosition + "'></a><img class='img-responsive' src='"+URL.createObjectURL(event.target.files[i])+"'></div>").fadeIn(1000);
         }

         $("#mul_" + p_multi).css("display","none");

         $("#mul_" + p_multi).detach().appendTo("#form_product");
         

         p_multi +=1;
         
         $("#form-multiple-image").append('<div id="mul_' + p_multi + '"><input type="file" class="form-control" id="images" name="images[]" onchange="preview_images()" /></div>');

         imgPosition +=1;
      
      }
    
</script>

@endif

@if ($page === 'user')


<script type="text/javascript">
  $(function(){
    $(".edit_data").click(function(){
      var id = $(this).attr("data-id");
      var id_data = $("#id" + id).text();
      var name_data = $("#name" + id).text();
      $("#form-action").attr("action", "{{ \App\Http\Controllers\Sugihart\DeviceManagerController::pageUrl('main/update_user') }}");
      $("input[name='id']").val(id_data);
      var email = $("#email" + id).text();
      $("input[name='name']").val(name_data);
      $("input[name='email']").val(email);
      $("input[name='password']").val("terlalu");


      $('#modal-success').modal('show'); 
    });
    $("#add_data").on('click',function(){
      $("#form-action").attr("action","{{ \App\Http\Controllers\Sugihart\DeviceManagerController::pageUrl('main/create_user') }}");
      $("input[name='id']").val("");
      $("input[name='name']").val("");
      $("input[name='email']").val("");
      $("input[name='password']").val("");
    })
  })
</script>

@endif

@if ($page === 'vendor')


<script type="text/javascript">
  $(function(){
    $(".edit_data").click(function(){
      var id = $(this).attr("data-id");
      var id_data = $("#id" + id).text();
      var name = $("#name" + id).text();
      var token = $("#token" + id).text();

      $("#form-action").attr("action","{{ \App\Http\Controllers\Sugihart\DeviceManagerController::pageUrl('main/update_vendor') }}");

      $("input[name='id']").val(id_data);
      $("input[name='name']").val(name);
      $("input[name='token']").val(token);

      $("input[name='token']").val(maketoken(30));
      $('#modal-success').modal('show'); 
    });
    $("#add_data").on('click',function(){
      $("#form-action").attr("action","{{ \App\Http\Controllers\Sugihart\DeviceManagerController::pageUrl('main/create_vendor') }}");
      $("input[name='id']").val("");
      $("input[name='name']").val("");
      $("input[name='token']").val(maketoken(30));
    })
    function maketoken(length) {
       var result           = '';
       var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
       var charactersLength = characters.length;
       for ( var i = 0; i < length; i++ ) {
          result += characters.charAt(Math.floor(Math.random() * charactersLength));
       }
       return result;
    }
  })
</script>

@endif

@if ($page === 'device')


<script type="text/javascript">
  $(function(){

    $(".create_sub").click(function(){
      var id = $(this).attr("data-id");
      var type = $("#type" + id).text();
      var token = $("#token" + id).text();

      $("#form-action").attr("action","{{ \App\Http\Controllers\Sugihart\DeviceManagerController::pageUrl('main/create_device') }}");

      $("input[name='token']").val(token);
      $("#type option[value='" +  type  + "']").prop('selected', true);

      $('#modal-success').modal('show'); 
    });
    $(".edit_data").click(function(){
      var id = $(this).attr("data-id");
      var id_data = $("#id" + id).text();
      var name = $("#name" + id).text();
      var url_device = $("#url_device" + id).text();
      var type = $("#type" + id).text();
      var ip_access = $("#ip_access" + id).text();
      var token = $("#token" + id).text();

      $("#form-action").attr("action","{{ \App\Http\Controllers\Sugihart\DeviceManagerController::pageUrl('main/update_device') }}");

      $("input[name='id']").val(id_data);
      $("input[name='name']").val(name);
      $("input[name='url_device']").val(url_device);
      $("input[name='ip_access']").val(ip_access);
      $("input[name='token']").val(token);
      $("#type option[value='" +  type  + "']").prop('selected', true);

      $('#modal-success').modal('show'); 
    });
    $("#add_data").on('click',function(){
      $("#form-action").attr("action","{{ \App\Http\Controllers\Sugihart\DeviceManagerController::pageUrl('main/create_device') }}");
      $("input[name='id']").val("");
      $("input[name='name']").val("");
      $("input[name='url_device']").val("");
      $("input[name='ip_access']").val("");
      $("#type option[value='']").prop('selected', true);
      $("input[name='token']").val(maketoken(30));
    })
    function maketoken(length) {
       var result           = '';
       var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
       var charactersLength = characters.length;
       for ( var i = 0; i < length; i++ ) {
          result += characters.charAt(Math.floor(Math.random() * charactersLength));
       }
       return result;
    }
  })
</script>

@endif

@if ($page === 'log')

@endif

</body>

</html>

@elseif ($screen === 'login')

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <title>HUTAMKARYA | Login</title>
        <meta name="description" content="Elmer is a Dashboard & Admin Site Responsive Template by hencework." />
        <meta name="keywords" content="admin, admin dashboard, admin template, cms, crm, Elmer Admin, Elmeradmin, premium admin templates, responsive admin, sass, panel, software, ui, visualization, web app, application" />
        <meta name="author" content="hencework"/>
         
  <!-- Favicon -->
  <link rel="shortcut icon" href="{{\App\Http\Controllers\Sugihart\DeviceManagerController::assetUrl('elmer/dist/img/user.png') }}" />
 
        
        <!-- vector map CSS -->
        <link href="{{ \App\Http\Controllers\Sugihart\DeviceManagerController::assetUrl('elmer/vendors/bower_components/jasny-bootstrap/dist/css/jasny-bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
        
        
        
        <!-- Custom CSS -->
        <link href="{{ \App\Http\Controllers\Sugihart\DeviceManagerController::assetUrl('elmer/dist/css/style.css') }}" rel="stylesheet" type="text/css">
    </head>
    <body>
        <!--Preloader-->
        <div class="preloader-it">
            <div class="la-anim-1"></div>
        </div>
        <!--/Preloader-->
        
        <div class="wrapper  pa-0">
            <header class="sp-header">
                <div class="sp-logo-wrap pull-left">
                    <a href="index.html">
                        <img class="brand-img mr-10" src="{{\App\Http\Controllers\Sugihart\DeviceManagerController::assetUrl('elmer/dist/img/user.png') }}" alt="brand" style="width: 30px;height: 30px;" />
                        <span class="brand-text">HUTAMAKARYA</span>
                    </a>
                </div>
                <!-- <div class="form-group mb-0 pull-right">
                    <span class="inline-block pr-10">Don't have an account?</span>
                    <a class="inline-block btn btn-primary  btn-rounded btn-outline" href="signup.html">Sign Up</a>
                </div> -->
                <div class="clearfix"></div>
            </header>
            
            <!-- Main Content -->
            <div class="page-wrapper pa-0 ma-0 auth-page">
                <div class="container-fluid">
                    <!-- Row -->
                    <div class="table-struct full-width full-height">
                        <div class="table-cell vertical-align-middle auth-form-wrap">
                            <div class="auth-form  ml-auto mr-auto no-float">
                                <div class="row">
                                    <div class="col-sm-12 col-xs-12">
                                        <div class="mb-30">
                                            <h3 class="text-center txt-dark mb-10">Sign in to Hutamakarya</h3>
                                            <h6 class="text-center nonecase-font txt-grey">Enter your details below</h6>
                                        </div>  
                                        <div class="form-wrap">
                                            @if (session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
                                            <form method="POST" action="{{ route('sdm.login') }}">
                                                @csrf
                                                <div class="form-group">
                                                    <label class="control-label mb-10" for="exampleInputEmail_2">Email address</label>
                                                    <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus id="exampleInputEmail_2" placeholder="Enter email" >

                                                    @if ($errors->has('email'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('email') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="form-group">
                                                    <label class="pull-left control-label mb-10" for="exampleInputpwd_2">Password</label>

                                                    <div class="clearfix"></div>

                                                    <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" required="" id="exampleInputpwd_2" placeholder="Enter pwd" name="password">

                                                     @if (Route::has('sdm.password.request'))
                                                        <a class="capitalize-font txt-primary block mb-10 pull-right font-12" href="{{ route('sdm.password.request') }}">{{ __('Forgot Your Password?') }}</a>
                                                    @endif
                                                    @if ($errors->has('password'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('password') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                                
                                                <div class="form-group">
                                                    <div class="checkbox checkbox-primary pr-10 pull-left">
                                                        <input id="checkbox_2" name="remember" value="1" type="checkbox">
                                                        <label for="checkbox_2"> Keep me logged in</label>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                </div>
                                                <div class="form-group text-center">
                                                    <button type="submit" class="btn btn-primary  btn-rounded">sign in</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>  
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Row -->   
                </div>
                
            </div>
            <!-- /Main Content -->
        
        </div>
        <!-- /#wrapper -->
        
        <!-- JavaScript -->
        
        <!-- jQuery -->
        <script src="{{ \App\Http\Controllers\Sugihart\DeviceManagerController::assetUrl('elmer/vendors/bower_components/jquery/dist/jquery.min.js') }}"></script>
        
        <!-- Bootstrap Core JavaScript -->
        <script src="{{ \App\Http\Controllers\Sugihart\DeviceManagerController::assetUrl('elmer/vendors/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
        <script src="{{ \App\Http\Controllers\Sugihart\DeviceManagerController::assetUrl('elmer/vendors/bower_components/jasny-bootstrap/dist/js/jasny-bootstrap.min.js') }}"></script>
        
        <!-- Slimscroll JavaScript -->
        <script src="{{ \App\Http\Controllers\Sugihart\DeviceManagerController::assetUrl('elmer/dist/js/jquery.slimscroll.js') }}"></script>
        
        <!-- Init JavaScript -->
        <script src="{{ \App\Http\Controllers\Sugihart\DeviceManagerController::assetUrl('elmer/dist/js/init.js') }}"></script>
    </body>
</html>

@elseif ($screen === 'register')
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sugihart Device Manager</title>
    <link rel="stylesheet" href="{{ \App\Http\Controllers\Sugihart\DeviceManagerController::assetUrl('elmer/dist/css/style.css') }}">
</head>
<body>
    <main style="max-width:720px;margin:4rem auto;padding:1rem">
        <p><a href="{{ route('sdm.login') }}">Login Device Manager</a></p>
        
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('sdm.register') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

    </main>
</body>
</html>

@elseif ($screen === 'email')
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sugihart Device Manager</title>
    <link rel="stylesheet" href="{{ \App\Http\Controllers\Sugihart\DeviceManagerController::assetUrl('elmer/dist/css/style.css') }}">
</head>
<body>
    <main style="max-width:720px;margin:4rem auto;padding:1rem">
        <p><a href="{{ route('sdm.login') }}">Login Device Manager</a></p>
        
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('sdm.password.email') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Send Password Reset Link') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

    </main>
</body>
</html>

@elseif ($screen === 'reset')
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sugihart Device Manager</title>
    <link rel="stylesheet" href="{{ \App\Http\Controllers\Sugihart\DeviceManagerController::assetUrl('elmer/dist/css/style.css') }}">
</head>
<body>
    <main style="max-width:720px;margin:4rem auto;padding:1rem">
        <p><a href="{{ route('sdm.login') }}">Login Device Manager</a></p>
        
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('sdm.password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

    </main>
</body>
</html>

@endif
