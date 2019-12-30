<!DOCTYPE html>
<html>
<head>    
    <link rel="stylesheet" href="/hutamakarya-kios-live/css/bootstrap.min.css">
    <script src="/hutamakarya-kios-live/js/jquery-3.3.1.min.js"></script>
    <script src="/hutamakarya-kios-live/js/bootstrap.min.js"></script>
    <script src="/hutamakarya-kios-live/js/face-api.js"></script>


  <style>
body{
    overflow-x:hidden;
    overflow-y:hidden;
}
        #stylecam{
            background-color: black;
            width: 1080px;
            min-height: 1800px;
        }
        #inputVideo {
            position: absolute;
            z-index: 1;
        }
        
        #overlay {
            position: absolute;
            z-index: 2;
        }
        
        #main-wrap {
            width: 1100px;
            height: 937px;
            background-color: #ec1e24;
            position: absolute;
            z-index: 3;
            margin-top: 800px;
            padding-top: 100px;
        }
        
        #wrap-aegisheader {
            width: 800px;
            height: 90px;
            border-radius: 99px;
            position: absolute;
            z-index: 4;
            margin-top: 755px;
            margin-left: 160px;
            background-color: #e44d52;
        }
        
        table tr td {
            padding: 10px;
        }
        
        .card {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            transition: 0.3s;
            width: 346.66666666667px;
            height: 150px;
            background-color: #e44d52;
            border-radius: 5px;
            margin-top: 40px;
            margin-bottom: 80px;
        }
        
        .card img {
            /*border-radius: 50%;*/
            border-radius: 10px;
            margin-top: -100px;
            vertical-align: middle;
            width: 150px;
            height: 150px;
            max-height: 150px;
            max-width: 150px;
            background-color: #fefefe;
        }
        
        .card:hover {
            box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
        }
        
        .containers {
            padding: 2px 16px;
            background-color: #e44d52;
            color: white;
        }
        
        .containers .known {
            color: green;
        }
        
        .containers .unknown {
            color: red;
        }
        
        .containers .label1,
        .label2,
        .label3 {
            width: 100%;
            font-style: italic;
            text-align: center;
        }
        
        .containers .label1 {
            font-size: 30px;
        }
        
        .containers .label2 {
            font-size: 25px;
        }
        
        .containers .label3 {
            font-size: 20px;
        }
        
        #running_text_adv {
            width: 1100px;
            height: 168px;
            background-color: black;
            position: absolute;
            z-index: 4;
            /*margin-top: 1794px;*/
            margin-top: 1734px;
        }
    </style>
    <style type="text/css">
        #carousel-custom,#carousel-custom2 {
            margin-top: 10px;
            width: 100%;
        }
        
        #carousel-custom,#carousel-custom2 .carousel-indicators {
            margin: 0px 0 0;
            overflow: auto;
            position: static;
            text-align: left;
            white-space: nowrap;
            width: 100%;
        }
        
        #carousel-custom,#carousel-custom2 .carousel-indicators li {
            background-color: transparent;
            -webkit-border-radius: 0;
            border-radius: 0;
            display: inline-block;
            height: auto;
            margin: 0 !important;
            width: auto;
        }
        
        #carousel-custom,#carousel-custom2 .carousel-indicators li img {
            display: block;
            opacity: 0.5;
        }
        
        #carousel-custom,#carousel-custom2 .carousel-indicators li.active img {
            opacity: 1;
        }
        
        #carousel-custom,#carousel-custom2 .carousel-indicators li:hover img {
            opacity: 0.75;
        }
        
        #carousel-custom,#carousel-custom2 .carousel-outer {
            position: relative;
        }
        
        .carousel-control {
            background-color: black;
            font-size: 40px;
        }
        
        .carousel-control.left {
            background-image: none;
            width: 54px;
            height: 54px;
            top: 50%;
            left: 20px;
            margin-top: -27px;
            line-height: 54px;
            border: 2px solid #fff;
            opacity: 1;
            text-shadow: none;
            -webkit-transition: all 0.2s ease-in-out 0s;
            -o-transition: all 0.2s ease-in-out 0s;
            transition: all 0.2s ease-in-out 0s;
        }
        
        .carousel-control.right {
            background-image: none;
            width: 54px;
            height: 54px;
            top: 50%;
            right: 20px;
            margin-top: -27px;
            line-height: 54px;
            border: 2px solid #fff;
            opacity: 1;
            text-shadow: none;
            -webkit-transition: all 0.2s ease-in-out 0s;
            -o-transition: all 0.2s ease-in-out 0s;
            transition: all 0.2s ease-in-out 0s;
        }
        #adv_style2{
            background-color: black;
            width: 100%;
            height: 1920px;
            display: none;
        }
        #adv_style2 .item{
            height: 1920px;
        }
        #adv_style1 .item video {
            width: 100%;
            height: 1920px;
        }

        #adv_style1 {
            position: absolute;
            z-index: 20;
            padding: 0px;
            display: none;
            margin-top: 230px;
        }
        #adv_style1 .item{
            height: 860px;
        }

        #adv_style1,#adv_style2 .item img {
            width: 100%;
        }
        
        #adv_style1 .item video {
            width: 100%;
            height: 860px;
        }
        
        #wrap_thumbnail_carousel,#wrap_thumbnail_carousel2 img,
        #wrap_thumbnail_carousel,#wrap_thumbnail_carousel2 video {
            width: 220px;
            height: 87px;
        }
    </style>

</head>
<body>

<!-- Preloader -->
  <div class="modal fade bd-example-modal-lg" data-backdrop="static" data-keyboard="false" tabindex="-1" id="modal_loading">
    <div class="modal-dialog modal-sm">
        <div class="modal-content" style="width: 600px;background-color: #f4f4f4;margin-left: -170px;margin-top: 400px;">
          <!-- <img src="{{asset('/images/loading.gif') }}" style="width: 100%;"> -->
          <h5 style="text-align: center;" id="text_loading">Get Data From Server.. </h5>
        </div>
    </div>
  </div>
  
    <div id="adv_style2" class="container-fluid" >
        <div class="row">
            <div class="col-lg-12">
                <div id="carousel-custom2" class="carousel slide" >
                    <!-- Wrapper for slides 

          -->
                    <div class="carousel-inner" id="wrap_item_carousel2" role="listbox">

                    </div>
                    <!-- Controls -->
                    <!-- <a class="left carousel-control" href="#carousel-custom,#carousel-custom2" role="button" data-slide="prev">
                        <i class="fa fa fa-angle-left"></i>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#carousel-custom,#carousel-custom2" role="button" data-slide="next">
                        <i class="fa fa fa-angle-right"></i>
                        <span class="sr-only">Next</span>
                    </a> -->
                    <!-- Indicators -->
                    <ol class="carousel-indicators visible-sm-block hidden-xs-block visible-md-block visible-lg-block" id="wrap_thumbnail_carousel2">

                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div id="stylecam" >
        
        <div style="position: relative" class="margin">
          <video onloadedmetadata="onPlay(this)" width="1100" height="825" id="inputVideo" autoplay muted playsinline></video>
          <canvas id="overlay"  width="1100" height="825" />
        </div>
        <div id="wrap-aegisheader">
        </div>

        <div id="main-wrap">
            <div id="adv_style2" class="container-fluid" style="background-color: black;">
                <div class="row">
                    <div class="col-lg-12">
                        <div id="carousel-custom" class="carousel slide" >
                            <!-- Wrapper for slides 

                  -->
                            <div class="carousel-inner" id="wrap_item_carousel" role="listbox">

                            </div>
                            <!-- Controls -->
                            <!-- <a class="left carousel-control" href="#carousel-custom,#carousel-custom2" role="button" data-slide="prev">
                                <i class="fa fa fa-angle-left"></i>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="right carousel-control" href="#carousel-custom,#carousel-custom2" role="button" data-slide="next">
                                <i class="fa fa fa-angle-right"></i>
                                <span class="sr-only">Next</span>
                            </a> -->
                            <!-- Indicators -->
                            <ol class="carousel-indicators visible-sm-block hidden-xs-block visible-md-block visible-lg-block" id="wrap_thumbnail_carousel">

                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <table style="width: 100%;position: absolute;z-index: 10">
                <tr id="face_column1">
                    <td>
                        <div class="card" style="margin-top: 60px;">
                            <center><img src="https://lakewangaryschool.sa.edu.au/wp-content/uploads/2017/11/placeholder-profile-sq.jpg" alt="Avatar" id="img_profile0"></center>
                            <div class="containers">

                                <div class="label1" id="label_name0">-</div>
                                <div class="label2" id="label_nik0">-</div>
                                <div class="label3" id="label_gender0">-</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="card" style="margin-top: 60px;">
                            <center><img src="https://lakewangaryschool.sa.edu.au/wp-content/uploads/2017/11/placeholder-profile-sq.jpg" alt="Avatar" id="img_profile1"></center>
                            <div class="containers">

                                <div class="label1" id="label_name1">-</div>
                                <div class="label2" id="label_nik1">-</div>
                                <div class="label3" id="label_gender1">-</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="card" style="margin-top: 60px;">
                            <center><img src="https://lakewangaryschool.sa.edu.au/wp-content/uploads/2017/11/placeholder-profile-sq.jpg" alt="Avatar" id="img_profile2"></center>
                            <div class="containers">

                                <div class="label1" id="label_name2">-</div>
                                <div class="label2" id="label_nik2">-</div>
                                <div class="label3" id="label_gender2">-</div>
                            </div>
                        </div>
                    </td>

                </tr>
                <tr id="face_column2">
                    <td>
                        <div class="card">
                            <center><img src="https://lakewangaryschool.sa.edu.au/wp-content/uploads/2017/11/placeholder-profile-sq.jpg" alt="Avatar" id="img_profile3"></center>
                            <div class="containers">

                                <div class="label1" id="label_name3">-</div>
                                <div class="label2" id="label_nik3">-</div>
                                <div class="label3" id="label_gender3">-</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="card">
                            <center><img src="https://lakewangaryschool.sa.edu.au/wp-content/uploads/2017/11/placeholder-profile-sq.jpg" alt="Avatar" id="img_profile4"></center>
                            <div class="containers">

                                <div class="label1" id="label_name4">-</div>
                                <div class="label2" id="label_nik4">-</div>
                                <div class="label3" id="label_gender4">-</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="card">
                            <center><img src="https://lakewangaryschool.sa.edu.au/wp-content/uploads/2017/11/placeholder-profile-sq.jpg" alt="Avatar" id="img_profile5"></center>
                            <div class="containers">

                                <div class="label1" id="label_name5">-</div>
                                <div class="label2" id="label_nik5">-</div>
                                <div class="label3" id="label_gender5">-</div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr id="face_column3">
                    <td>
                        <div class="card">
                            <center><img src="https://lakewangaryschool.sa.edu.au/wp-content/uploads/2017/11/placeholder-profile-sq.jpg" alt="Avatar" id="img_profile6"></center>
                            <div class="containers">

                                <div class="label1" id="label_name6">-</div>
                                <div class="label2" id="label_nik6">-</div>
                                <div class="label3" id="label_gender6">-</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="card">
                            <center><img src="https://lakewangaryschool.sa.edu.au/wp-content/uploads/2017/11/placeholder-profile-sq.jpg" alt="Avatar" id="img_profile7"></center>
                            <div class="containers">

                                <div class="label1" id="label_name7">-</div>
                                <div class="label2" id="label_nik7">-</div>
                                <div class="label3" id="label_gender7">-</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="card">
                            <center><img src="https://lakewangaryschool.sa.edu.au/wp-content/uploads/2017/11/placeholder-profile-sq.jpg" alt="Avatar" id="img_profile8"></center>
                            <div class="containers">

                                <div class="label1" id="label_name8">-</div>
                                <div class="label2" id="label_nik8">-</div>
                                <div class="label3" id="label_gender8">-</div>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>

        </div>
        <div id="running_text_adv">
            <marquee style="color: white;font-size: 50px;" id="running_text">Gunakanlah produk produk indonesia</marquee>

        </div>
    </div>




  </body>

  <script>
   
    const token_devie = "{{ $token }}";

    @include('render_kios.js.variabel_js')
    @include('render_kios.js.function_js')
    @include('render_kios.js.process_database_js')
    @include('render_kios.js.worker_js')
    @include('render_kios.js.styling_js')
    @include('render_kios.js.process_recognition_js')
    @include('render_kios.js.carousel_js')
    @include('render_kios.js.main_js')


  </script>
</body>
</html>
