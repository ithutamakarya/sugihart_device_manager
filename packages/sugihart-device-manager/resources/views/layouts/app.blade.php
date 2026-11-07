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
  <link rel="shortcut icon" href="{{\Sugihart\DeviceManager\Support\Urls::asset('/images/ic_aegis.jpeg') }}" />
  
  <!-- Data table CSS -->
  <link href="{{ \Sugihart\DeviceManager\Support\Urls::asset('elmer/vendors/bower_components/datatables/media/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css"/>
  
  <!-- select2 CSS -->
  <link href="{{ \Sugihart\DeviceManager\Support\Urls::asset('elmer/vendors/bower_components/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>

  <!-- bootstrap-select CSS -->
  <link href="{{ \Sugihart\DeviceManager\Support\Urls::asset('elmer/vendors/bower_components/bootstrap-select/dist/css/bootstrap-select.min.css') }}" rel="stylesheet" type="text/css"/>

  <link href="{{ \Sugihart\DeviceManager\Support\Urls::asset('elmer/vendors/bower_components/jquery-toast-plugin/dist/jquery.toast.min.css') }}" rel="stylesheet" type="text/css">
    
  <link rel="stylesheet" href="{{\Sugihart\DeviceManager\Support\Urls::asset('bootstrap-daterangepicker/daterangepicker.css')}}"  rel="stylesheet" type="text/css">
  
  <!-- Custom CSS -->
  <link href="{{ \Sugihart\DeviceManager\Support\Urls::asset('elmer/dist/css/style.css') }}" rel="stylesheet" type="text/css">


  <!-- Sweet Alert -->
  <link rel="stylesheet" href="{{\Sugihart\DeviceManager\Support\Urls::asset('css/sweetalert.css')}}">
  @yield('style')
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
    
    @include('sdm::_main_header')

    <!-- sidebar -->
    @include('sdm::_main_sidebar')
    
        <!-- Main Content -->
    <div class="page-wrapper">
      
      @if ($errors->any())
        <div class="alert alert-danger" role="alert"><ul>
          @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul></div>
      @endif
      @yield('content')

      @include('sdm::_main_footer')

      <!-- /Footer -->
      
    </div>
        <!-- /Main Content -->

    </div>
    <!-- /#wrapper -->
  
  <!-- JavaScript -->
  
    <!-- jQuery -->
    <script src="{{ \Sugihart\DeviceManager\Support\Urls::asset('elmer/vendors/bower_components/jquery/dist/jquery.min.js') }}"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="{{ \Sugihart\DeviceManager\Support\Urls::asset('elmer/vendors/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    
  <!-- Data table JavaScript -->
  <script src="{{ \Sugihart\DeviceManager\Support\Urls::asset('elmer/vendors/bower_components/datatables/media/js/jquery.dataTables.min.js') }}"></script>
  <script src="{{ \Sugihart\DeviceManager\Support\Urls::asset('elmer/dist/js/dataTables-data.js') }}"></script>
  

  <!-- Slimscroll JavaScript -->
  <script src="{{ \Sugihart\DeviceManager\Support\Urls::asset('elmer/dist/js/jquery.slimscroll.js') }}"></script>
  
  <!-- Progressbar Animation JavaScript -->
  <script src="{{ \Sugihart\DeviceManager\Support\Urls::asset('elmer/vendors/bower_components/waypoints/lib/jquery.waypoints.min.js') }}"></script>
  <script src="{{ \Sugihart\DeviceManager\Support\Urls::asset('elmer/vendors/bower_components/jquery.counterup/jquery.counterup.min.js') }}"></script>
  
  <!-- Fancy Dropdown JS -->
  <script src="{{ \Sugihart\DeviceManager\Support\Urls::asset('elmer/dist/js/dropdown-bootstrap-extended.js') }}"></script>
  
  <!-- Sparkline JavaScript -->
  <script src="{{ \Sugihart\DeviceManager\Support\Urls::asset('elmer/vendors/jquery.sparkline/dist/jquery.sparkline.min.js') }}"></script>

    
  <!-- Moment JavaScript -->
  <script type="text/javascript" src="{{ \Sugihart\DeviceManager\Support\Urls::asset('elmer/vendors/bower_components/moment/min/moment-with-locales.min.js') }}"></script>
  
      
  <!-- Bootstrap Datetimepicker JavaScript -->
  <script type="text/javascript" src="{{ \Sugihart\DeviceManager\Support\Urls::asset('elmer/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') }}"></script>

  <!-- Owl JavaScript -->
  <script src="{{ \Sugihart\DeviceManager\Support\Urls::asset('elmer/vendors/bower_components/owl.carousel/dist/owl.carousel.min.js') }}"></script>
  
  <!-- Switchery JavaScript -->
  <script src="{{ \Sugihart\DeviceManager\Support\Urls::asset('elmer/vendors/bower_components/switchery/dist/switchery.min.js') }}"></script>
  
  <!-- EChartJS JavaScript -->
  <script src="{{ \Sugihart\DeviceManager\Support\Urls::asset('elmer/vendors/bower_components/echarts/dist/echarts-en.min.js') }}"></script>
  <script src="{{ \Sugihart\DeviceManager\Support\Urls::asset('elmer/vendors/echarts-liquidfill.min.js') }}"></script>
  

  <!-- Bootstrap Select JavaScript -->
  <script src="{{ \Sugihart\DeviceManager\Support\Urls::asset('elmer/vendors/bower_components/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>

  <!-- Select2 JavaScript -->
  <script src="{{ \Sugihart\DeviceManager\Support\Urls::asset('elmer/vendors/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

  <!-- Toast JavaScript -->
  <script src="{{ \Sugihart\DeviceManager\Support\Urls::asset('elmer/vendors/bower_components/jquery-toast-plugin/dist/jquery.toast.min.js') }}"></script>
  
  <!-- Init JavaScript -->
  <script src="{{ \Sugihart\DeviceManager\Support\Urls::asset('elmer/dist/js/init.js') }}"></script><!-- 
  <script src="{{ \Sugihart\DeviceManager\Support\Urls::asset('elmer/dist/js/dashboard-data.js') }}"></script> -->

<script src="{{\Sugihart\DeviceManager\Support\Urls::asset('js/sweetalert.min.js') }}"></script>

    <!-- Tinymce JavaScript -->
    <script src="{{\Sugihart\DeviceManager\Support\Urls::asset('elmer/vendors/bower_components/tinymce/tinymce.min.js') }}"></script>
    <!-- Tinymce JavaScript -->
    <script src="{{\Sugihart\DeviceManager\Support\Urls::asset('elmer/dist/js/tinymce-data.js') }}"></script>
      

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

      @yield('scripts')
</body>

</html>
