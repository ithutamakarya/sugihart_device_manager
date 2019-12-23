@extends('layouts.app')

@section('content')
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
                                  <a href="javascript:void(0);" class="btn btn-success delete_data" data-url="{{ url('main/delete_device/'.$p->id.'/'.$vendor_id) }}">Delete</a>
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
                                  <a href="javascript:void(0);" class="btn btn-success delete_data" data-url="{{ url('main/delete_device/'.$p->id.'/'.$vendor_id) }}">Delete</a>
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
        <form method="post" action="{{ url('main/create_device') }}" id="form-action">
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
@endsection

@section('scripts')

<script type="text/javascript">
  $(function(){

    $(".create_sub").click(function(){
      var id = $(this).attr("data-id");
      var type = $("#type" + id).text();
      var token = $("#token" + id).text();

      $("#form-action").attr("action","{{ url('main/create_device') }}");

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

      $("#form-action").attr("action","{{ url('main/update_device') }}");

      $("input[name='id']").val(id_data);
      $("input[name='name']").val(name);
      $("input[name='url_device']").val(url_device);
      $("input[name='ip_access']").val(ip_access);
      $("input[name='token']").val(token);
      $("#type option[value='" +  type  + "']").prop('selected', true);

      $('#modal-success').modal('show'); 
    });
    $("#add_data").on('click',function(){
      $("#form-action").attr("action","{{ url('main/create_device') }}");
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
@endsection
