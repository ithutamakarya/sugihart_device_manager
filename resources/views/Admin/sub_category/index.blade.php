@extends('layouts.app')

@section('content')
<div class="container-fluid">
        
        <!-- Title -->
        <div class="row heading-bg">
          <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h5 class="txt-dark">Data Sub Category</h5>
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
                  <h6 class="panel-title txt-dark">Sub Category</h6>
                </div>
                <div class="pull-right">
                  <a href="javascript:;" class="btn btn-primary" data-toggle="modal" data-target="#modal-success" id="add_data">
                    Add Sub Category
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
                            <th>Category</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>

                        @foreach($sub_category as $row => $p)
                        
                        <tr>
                          <td >{{ $row + 1 }}</td>            
                          <td id="{{ 'name'.$row }}" >{{$p->name}}</td>
                          <td >
                            {{ $p->category->name }}
                            <label id="{{ 'category_id'.$row }}" style="display: none;">{{ $p->category->id }}</label>
                          </td>
                          
                          <td>
                            <a href="javascript:void(0);" class="btn btn-success delete_data" data-url="{{ url('/admin/delete_sub_category/'.$p->id) }}">Delete</a>
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
        <h4 class="modal-title">Add Category</h4>
      </div>
      <div class="modal-body">
        <form method="post" action="{{ url('/admin/create_sub_category') }}" id="form-action">
        {!! csrf_field() !!}
          <input required type="hidden" name="id">
          <div class="form-group">
            <label>Name</label>
            <input required type="text" class="form-control" placeholder="Name" name="name">
          </div>
          <div class="form-group">
            <label>Category</label>
            <select class="form-control" id="category_id" name="category_id" required>
                <option value=""></option>
              @foreach($category as $p)
                <option value="{{ $p->id }}">{{ $p->name }}</option>
              @endforeach
            </select>
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

      $("#form-action").attr("action","{{ url('/admin/update_sub_category') }}");

      $("input[name='id']").val(id_data);
      $("input[name='name']").val(name_data);
      $("#category_id option[value='" +  $("#category_id" + id).text()  + "']").prop('selected', true);
      $('#modal-success').modal('show'); 
    });
    $("#add_data").on('click',function(){
      $("#form-action").attr("action","{{ url('/admin/create_sub_category') }}");
      $("input[name='id']").val("");
      $("input[name='name']").val("");
      $("#category_id option[value='']").prop('selected', true);
      
    })
  })
</script>
@endsection
