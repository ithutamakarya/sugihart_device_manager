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

@endsection

@section('scripts')
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
@endsection
