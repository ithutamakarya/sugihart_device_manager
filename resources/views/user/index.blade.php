@extends('layouts.app')

@section('content')
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
                            <th>Phone</th>
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
                          <td id="{{ 'phone'.$row }}" >{{$p->phone}}</td>
                          <td style="display: none;">{{$p->regid}}
                          <p id="{{ 'address'.$row }}" class="invisible_data">{{$p->address}}</p>
                          <p id="{{ 'id'.$row }}" class="invisible_data">{{ $p->id }}</p></td>
                          <td >
                          <!-- /user/view/{{$p->id}} -->
                            <a href="javascript:void(0);" class="btn btn-success delete_data" data-url="/delete_user/{{ $p->id }}">Delete</a>
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
        <form method="post" action="/create_user" id="form-action">
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
          <div class="form-group">
            <label>Phone</label>
            <input required type="number" class="form-control" placeholder="Phone" name="phone">
          </div>
          <div class="form-group">
            <label>Address</label>
            <textarea required class="form-control" placeholder="Address" name="address"></textarea>
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
    $(".edit_data").click(function(){
      var id = $(this).attr("data-id");
      var id_data = $("#id" + id).text();
      var name_data = $("#name" + id).text();
      var ocu_data = $("#email" + id).text();
      var address_data = $("#address" + id).text();
      var phone_data = $("#phone" + id).text();

      $("#form-action").attr("action","/update_user");

      $("input[name='id']").val(id_data);
      $("input[name='name']").val(name_data);
      $("input[name='email']").val(ocu_data);
      $("input[name='password']").val("terlalu");
      $("input[name='phone']").val(phone_data);

      $("textarea[name='address']").text(address_data);

      $('#modal-success').modal('show'); 
    });
    $("#add_data").on('click',function(){
      $("#form-action").attr("action","/create_user");
      $("input[name='id']").val("");
      $("input[name='name']").val("");
      $("input[name='email']").val("");
      $("input[name='password']").val("");
      $("input[name='phone']").val("");
      $("textarea[name='address']").text("");
      
    })
  })
</script>
@endsection
