@extends('layouts.app')

@section('content')
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

@endsection
