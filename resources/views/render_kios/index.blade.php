<!DOCTYPE html>
<html>
<head>

    <link rel="stylesheet" href="public/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <script src="public/js/jquery-3.3.1.min.js"></script>
    <script src="public/js/bootstrap.min.js"></script>
<style>
    body{
        overflow-y: hidden;
        overflow-x: hidden;
    }
        #stylecam{
            background-color: black;
            width: 100%; /*1100px*/
            height: 1920px; /*100%*/
        }
        #capture{
            width: 100%;
            position: absolute;
            z-index: 1;
        }
        
        #main-wrap {
            width: 100%; /*1100px*/
            height: 100%; /*1100px*/
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
            width: 340px;
            height: 190px;
            background-color: #e44d52;
            border-radius: 5px;
            margin-top: 20px;
            margin-bottom: 80px;
        }
        
        .img-circle {
            /*border-radius: 50%;*/
            border-radius: 999px;
            vertical-align: middle;
            width: 150px;
            height: 150px;
            background-color: #fefefe;
        }
        .card .img-circle{
            margin-top: -100px;
            max-height: 150px;
            max-width: 150px;
        }
        .card_absent {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            transition: 0.3s;
            width: 100%;
            height: 100px;
            background-color: #e44d52;
            border-radius: 5px;
            margin-top: 40px;
            margin-bottom: 100px;
        }
        
        .card_absent img {
            /*border-radius: 50%;*/
            border-radius: 99px;
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
            width: 100%; /*1100px*/
            height: 100px;
            background-color: black;
            position: absolute;
            z-index: 4;
            /*margin-top: 1794px;*/
            margin-top: 1820px;
        }
        .col-lg-12{
            padding: 0px;
        }
        #carousel-custom {
            margin-top: 10px;
            width: 100%;
        }
        
        #carousel-custom .carousel-indicators {
            margin: 0px 0 0;
            overflow: auto;
            position: static;
            text-align: left;
            white-space: nowrap;
            width: 100%;
        }
        
        #carousel-custom .carousel-indicators li {
            background-color: transparent;
            -webkit-border-radius: 0;
            border-radius: 0;
            display: inline-block;
            height: auto;
            margin: 0 !important;
            width: auto;
        }
        
        #carousel-custom .carousel-indicators li img {
            display: block;
            opacity: 0.5;
        }
        
        #carousel-custom .carousel-indicators li.active img {
            opacity: 1;
        }
        
        #carousel-custom .carousel-indicators li:hover img {
            opacity: 0.75;
        }
        
        #carousel-custom .carousel-outer {
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
        #adv_style1 .item img {
            width: 100%;
        }
        
        #adv_style1 .item video {
            width: 100%;
            height: 860px;
        }
        
        #wrap_thumbnail_carousel img,
        #wrap_thumbnail_carousel video {
            width: 220px;
            height: 87px;
        }

        #adv_style2{
            padding: 0px;
            display: none;
        }
        #adv_style2 .item{
            height: 1820px;
            position: relative;
        }
        #adv_style2 .item img {
            position: absolute;   
            top: 50%;
            transform: translateY(-50%);
        }
        
        #adv_style2 .item video {
            width: 100%;
            height: 1820px;
        }
        .td_column3{
            padding-top: 80px;
        }
        /*LEADERBOARD*/
        #adv_style_leaderboard{
            background-color: #f1f1f1;
            width: 100%; /*1100px*/
            height: 1920px;
            display: none;
        }
        #wrap_top{
            width: 100%;
            height: 40%;
            padding-top:70px;
            padding: 20px;
            background-color: #ec1e24;
        }
        #adv_style_leaderboard p.title{
            color: white;
            font-size: 80px;
            text-align: center;
            margin-bottom: 30px;
        }
        #adv_style_leaderboard .img-circle.img-top-leaderboard{
            
            width: 270px;
            height: 270px;
            vertical-align: middle;
            margin-bottom: 20px;
        }
        .card_leaderboard {
          box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
          padding: 16px;
          height: 250px;
          text-align: center;
          background-color: white;
          border-radius: 40px;
          padding: 40px 20px;
        }
        .card_leaderboard h1{
            color: #ec1e24;
            font-size: 30px;
            letter-spacing: 1px;
            font-weight: bold;
            text-shadow: 1px 1px #ff0000;
        }
        .card_leaderboard p{
            color: #ec1e24;
            font-size: 20px;
            letter-spacing: 1px;
            font-weight: bold;
        }
        .card_leaderboard h2{
            color: #ec1e24;
            font-size: 35px;
            letter-spacing: 1px;
            font-weight: bold;
        }
        .wrap_number_top{
            position: absolute;
            z-index: 2;
            width: 100px;
            height: 100px;
        }
        #wrap_leaderboard2{
            padding: 100px 50px;
            width: 100%;
            height: 100%;
        }
        .card_leaderboard2{
          box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
          padding: 5px;
          height: 140px;
          margin-bottom: 15px;
          text-align: center;
          background-color: #ec1e24;
          border-radius: 40px;
        }
        .card_leaderboard2 .number_box{
          box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
          width: 100px;
          height: 100px;
          border-radius: 20px;
          background-color: white;
          padding-top: 5px;
        }
        .card_leaderboard2 .number_box p{
            font-size: 60px;
            font-weight: bold;
            color: #ec1e24;
            margin-left: -5px;
        }
        .img-leaderboard{
            width: 110px;
            height: 110px;
        }
        .card_leaderboard2 .leaderboard_name{
            color: white;
            font-size: 40px;
            text-align: left;   
        }
        .card_leaderboard2 .wrap_time{
            width: 200px;
            background-color: white;
            border-radius: 20px;
            padding: 10px;

        }
        .card_leaderboard2 .wrap_time p{
            color: black;
            font-weight: bold;
            text-align: center;
            font-size: 40px; 
        }


    </style>

</head>
<body>
    <div id="adv_style2">
    </div>
    <div id="adv_style_leaderboard">
        <div id="wrap_top">
            <p class="title">Leaderboards</p>
            <table style="width: 100%;">
                <tr>
                    <?php $number = [2,1,3] ?>
                    <?php for($i=0;$i<=2;$i++){ ?>
                    <td>
                        <div class="wrap_number_top">
                            <p class="title" style="background-color: black;font-weight: bold;"><?= $number[$i] ?></p>
                        </div>
                        <center>
                            <img class="img-circle img-top-leaderboard"  src="public/image/placeholder-profile.jpg" alt="Avatar" id="leaderboard_img<?= $number[$i] ?>">
                            <div class="card_leaderboard">
                                <h1 id="leaderboard_name<?= $number[$i] ?>">-</h1>
                                <p id="leaderboard_division<?= $number[$i] ?>">-</p>
                                <h2 id="leaderboard_time<?= $number[$i] ?>">-</h2>
                            </div>
                        </center>
                    </td>
                    <?php } ?>
                </tr>
            </table>
            <div id="wrap_leaderboard2">
                <?php for($i=4;$i<=10;$i++){ ?>
                    <div class="card_leaderboard2">
                        <table style="width: 100%;">
                            <tr>
                                <td style="width: 12%">
                                    <div class="number_box">
                                        <p ><?= $i ?></p>
                                    </div>
                                </td>
                                <td style="width: 15%">
                                    <img id="leaderboard_img<?= $i ?>" class="img-circle img-leaderboard" src="public/image/placeholder-profile.jpg" alt="Avatar" >
                                </td>
                                <td style="width: 50%;">
                                    <p class="leaderboard_name" id="leaderboard_name<?= $i ?>">-</p>
                                </td>
                                <td style="width: 23%;">
                                    <div class="wrap_time">
                                        <p id="leaderboard_time<?= $i ?>">-</p>
                                        <p id="leaderboard_division<?= $i ?>" style="display: none">-</p>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        
                    </div>

                <?php } ?>
            </div>
        </div>
    </div>
    <div id="stylecam">
        
        <div style="position: relative" class="margin">
            <img src="public/image/capture.jpg" id="capture"  />
        </div>
        <div id="wrap-aegisheader">
            <h1 style="text-align: center;color: white;width: 100%;font-weight: bold;">- ATTENDANCE LIST -</h1>
        </div>

        <div id="main-wrap">
            <div id="adv_style1">
                <div id="carousel_style" class="container-fluid" style="background-color: black;">
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="carousel-custom" class="carousel slide">
                                <div class="carousel-inner" id="wrap_item_carousel" role="listbox">
                                </div>
                                <!-- Indicators -->
                                <ol class="carousel-indicators visible-sm-block hidden-xs-block visible-md-block visible-lg-block" id="wrap_thumbnail_carousel" style="margin-top: -10px">

                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <table style="width: 100%;position: absolute;z-index: 10;margin-top: 30px;">
                <tr id="face_column1">
                    <td>
                        <div class="card" style="margin-top: 60px;">
                            <center><img class="img-circle" src="public/image/placeholder-profile.jpg" alt="Avatar" id="img_profile0"></center>
                            <div class="containers">

                                <div class="label1" id="label_name0">.</div>
                                <div class="label2" id="label_nik0">.</div>
                                <div class="label3" id="label_department0">.</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="card" style="margin-top: 60px;">
                            <center><img class="img-circle" src="public/image/placeholder-profile.jpg" alt="Avatar" id="img_profile1"></center>
                            <div class="containers">

                                <div class="label1" id="label_name1">.</div>
                                <div class="label2" id="label_nik1">.</div>
                                <div class="label3" id="label_department1">.</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="card" style="margin-top: 60px;">
                            <center><img class="img-circle" src="public/image/placeholder-profile.jpg" alt="Avatar" id="img_profile2"></center>
                            <div class="containers">

                                <div class="label1" id="label_name2">.</div>
                                <div class="label2" id="label_nik2">.</div>
                                <div class="label3" id="label_department2">.</div>
                            </div>
                        </div>
                    </td>

                </tr>
                <tr id="face_column2">
                    <td>
                        <div class="card" style="margin-bottom: 10px;">
                            <center><img class="img-circle" src="public/image/placeholder-profile.jpg" alt="Avatar" id="img_profile3"></center>
                            <div class="containers">

                                <div class="label1" id="label_name3">.</div>
                                <div class="label2" id="label_nik3">.</div>
                                <div class="label3" id="label_department3">.</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="card" style="margin-bottom: 10px;">
                            <center><img class="img-circle" src="public/image/placeholder-profile.jpg" alt="Avatar" id="img_profile4"></center>
                            <div class="containers">

                                <div class="label1" id="label_name4">.</div>
                                <div class="label2" id="label_nik4">.</div>
                                <div class="label3" id="label_department4">.</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="card" style="margin-bottom: 10px;">
                            <center><img class="img-circle" src="public/image/placeholder-profile.jpg" alt="Avatar" id="img_profile5"></center>
                            <div class="containers">

                                <div class="label1" id="label_name5">.</div>
                                <div class="label2" id="label_nik5">.</div>
                                <div class="label3" id="label_department5">.</div>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr id="face_column3">
                    <td class="td_column3">
                        <div class="card" style="margin-bottom: 10px;">
                            <center><img class="img-circle" src="public/image/placeholder-profile.jpg" alt="Avatar" id="img_profile6"></center>
                            <div class="containers">

                                <div class="label1" id="label_name6">.</div>
                                <div class="label2" id="label_nik6">.</div>
                                <div class="label3" id="label_department6">.</div>
                            </div>
                        </div>
                    </td>
                    <td class="td_column3">
                        <div class="card" style="margin-bottom: 10px;">
                            <center><img class="img-circle" src="public/image/placeholder-profile.jpg" alt="Avatar" id="img_profile7"></center>
                            <div class="containers">

                                <div class="label1" id="label_name7">.</div>
                                <div class="label2" id="label_nik7">.</div>
                                <div class="label3" id="label_department7">.</div>
                            </div>
                        </div>
                    </td>
                    <td class="td_column3">
                        <div class="card" style="margin-bottom: 10px;">
                            <center><img class="img-circle" src="public/image/placeholder-profile.jpg" alt="Avatar" id="img_profile8"></center>
                            <div class="containers">

                                <div class="label1" id="label_name8">.</div>
                                <div class="label2" id="label_nik8">.</div>
                                <div class="label3" id="label_department8">.</div>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>

        </div>
        <div id="running_text_adv">
            <marquee style="color: white;font-size: 50px;" id="running_text">SELAMAT DATANG DI HUTAMAKARYA</marquee>
        </div>
    </div>




  </body>
  
    <script type="text/javascript">  
        const token_device = "{{ $token }}";

        @include('render_kios.js.variabel_js')
        @include('render_kios.js.function_js')
        @include('render_kios.js.process_database_js')
        @include('render_kios.js.worker_js')
        @include('render_kios.js.styling_js')
        @include('render_kios.js.carousel_js')
        @include('render_kios.js.main_js')
    </script>
</body>
</html>
