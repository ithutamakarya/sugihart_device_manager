@extends('layouts.app')

@section('content')
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
            <div class="panel panel-default card-view">
              <div class="panel-heading">
                <div class="pull-left">
                  <h6 class="panel-title txt-dark">Dashboard</h6>
                </div>
                <div class="clearfix"></div>
              </div>
              <div class="panel-wrapper collapse in">
                <div class="panel-body">
                  <div class="row">
                      
                  </div>
                </div>
              </div>
            </div>  
          </div>
        </div>
        <!-- /Row -->
        
 
      </div>

@endsection

@section('scripts')

@endsection
