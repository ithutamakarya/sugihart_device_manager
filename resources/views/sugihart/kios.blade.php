<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="{{$pc_local}}public/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <script src="{{$pc_local}}public/js/jquery-3.3.1.min.js"></script>
    <script src="{{$pc_local}}public/js/bootstrap.min.js"></script>
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
            height: 100%;
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
            margin-top: 330px;
        }
        #adv_style1 .item{
            height: 760px;
            width: 1080px;
        }
        #adv_style1 .item img {
            width: 100%;
        }
        
        #adv_style1 .item video {
            width: 100%;
            height: 100%;
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
                            <img class="img-circle img-top-leaderboard"  src="{{$pc_local}}public/image/placeholder-profile.jpg" alt="Avatar" id="leaderboard_img<?= $number[$i] ?>">
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
                                    <img id="leaderboard_img<?= $i ?>" class="img-circle img-leaderboard" src="{{$pc_local}}public/image/placeholder-profile.jpg" alt="Avatar" >
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
            <img src="{{$pc_local}}public/image/capture.jpg" id="capture"  />
        </div>
        <div id="wrap-aegisheader">
            <h1 style="text-align: center;color: white;width: 100%;font-weight: bold;">- ATTENDANCE LIST -</h1>
        </div>

        <div id="main-wrap">
            <div id="adv_style1">
                <div id="carousel_style" class="container-fluid" style="background-color: black;height: 1920px;">
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
                            <center><img class="img-circle" src="{{$pc_local}}public/image/placeholder-profile.jpg" alt="Avatar" id="img_profile0"></center>
                            <div class="containers">

                                <div class="label1" id="label_name0">.</div>
                                <div class="label2" id="label_time0">.</div>
                                <div class="label3" id="label_divisi0">.</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="card" style="margin-top: 60px;">
                            <center><img class="img-circle" src="{{$pc_local}}public/image/placeholder-profile.jpg" alt="Avatar" id="img_profile1"></center>
                            <div class="containers">

                                <div class="label1" id="label_name1">.</div>
                                <div class="label2" id="label_time1">.</div>
                                <div class="label3" id="label_divisi1">.</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="card" style="margin-top: 60px;">
                            <center><img class="img-circle" src="{{$pc_local}}public/image/placeholder-profile.jpg" alt="Avatar" id="img_profile2"></center>
                            <div class="containers">

                                <div class="label1" id="label_name2">.</div>
                                <div class="label2" id="label_time2">.</div>
                                <div class="label3" id="label_divisi2">.</div>
                            </div>
                        </div>
                    </td>

                </tr>
                <tr id="face_column2">
                    <td>
                        <div class="card" style="margin-bottom: 10px;">
                            <center><img class="img-circle" src="{{$pc_local}}public/image/placeholder-profile.jpg" alt="Avatar" id="img_profile3"></center>
                            <div class="containers">

                                <div class="label1" id="label_name3">.</div>
                                <div class="label2" id="label_time3">.</div>
                                <div class="label3" id="label_divisi3">.</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="card" style="margin-bottom: 10px;">
                            <center><img class="img-circle" src="{{$pc_local}}public/image/placeholder-profile.jpg" alt="Avatar" id="img_profile4"></center>
                            <div class="containers">

                                <div class="label1" id="label_name4">.</div>
                                <div class="label2" id="label_time4">.</div>
                                <div class="label3" id="label_divisi4">.</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="card" style="margin-bottom: 10px;">
                            <center><img class="img-circle" src="{{$pc_local}}public/image/placeholder-profile.jpg" alt="Avatar" id="img_profile5"></center>
                            <div class="containers">

                                <div class="label1" id="label_name5">.</div>
                                <div class="label2" id="label_time5">.</div>
                                <div class="label3" id="label_divisi5">.</div>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr id="face_column3">
                    <td class="td_column3">
                        <div class="card" style="margin-bottom: 10px;">
                            <center><img class="img-circle" src="{{$pc_local}}public/image/placeholder-profile.jpg" alt="Avatar" id="img_profile6"></center>
                            <div class="containers">

                                <div class="label1" id="label_name6">.</div>
                                <div class="label2" id="label_time6">.</div>
                                <div class="label3" id="label_divisi6">.</div>
                            </div>
                        </div>
                    </td>
                    <td class="td_column3">
                        <div class="card" style="margin-bottom: 10px;">
                            <center><img class="img-circle" src="{{$pc_local}}public/image/placeholder-profile.jpg" alt="Avatar" id="img_profile7"></center>
                            <div class="containers">

                                <div class="label1" id="label_name7">.</div>
                                <div class="label2" id="label_time7">.</div>
                                <div class="label3" id="label_divisi7">.</div>
                            </div>
                        </div>
                    </td>
                    <td class="td_column3">
                        <div class="card" style="margin-bottom: 10px;">
                            <center><img class="img-circle" src="{{$pc_local}}public/image/placeholder-profile.jpg" alt="Avatar" id="img_profile8"></center>
                            <div class="containers">

                                <div class="label1" id="label_name8">.</div>
                                <div class="label2" id="label_time8">.</div>
                                <div class="label3" id="label_divisi8">.</div>
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
        const token_device = {{ Illuminate\Support\Js::from($token) }};
        $(document).ready(function(){
            setTimeout(function () {
                if(window.location.hash != '#r') {
                    window.location.hash = 'r';
                    window.location.reload(1);
                }
            }, 2000);
        });
        
let forwardTimes = [];
const kiosk_asset_url = {{ Illuminate\Support\Js::from($pc_local) }};
var def_image = kiosk_asset_url + 'public/image/placeholder-profile.jpg';

const base_url = {{ Illuminate\Support\Js::from(rtrim(config('sugihart-device-manager.attendance_url'), '/') . '/') }};
const base_url_api = {{ Illuminate\Support\Js::from(rtrim(config('sugihart-device-manager.kiosk_local_url'), '/') . '/') }};

console.log(base_url);
const base_src_uploads = base_url + "uploads";

var worker_check_display;
var worker_check_data_train;
var worker_office_hour;
var worker_running_text;

var available_voices;
var synth = window.speechSynthesis;

var is_attendance_hour = "";

let start_work = "00:00";
let start_work_until = "00:00";
let end_work = "00:00";
let end_work_until = "00:00";


var style_now = "default";

var length_carousel = 0;
var media_type = [];

var pos_carousel_now = 1;
var onclick_thumbnail = false;
var on_video_run = false;

var worker_video;
let count_data_training = 0;


var on_cam_play = true;
var video_duration = [];
var n_employee_ontime = 0;

var is_already_check_ontime = false;
var timer_office = 3000;
var timer_display = 1000;
var timer_train_data = 2000;
var timer_running_text = 1000;

var running_text = "";
var on_change_display = false;
var myvideo;


        
function isJson(item) {
    item = typeof item !== "string"
        ? JSON.stringify(item)
        : item;

    try {
        item = JSON.parse(item);
    } catch (e) {
        return false;
    }

    if (typeof item === "object" && item !== null) {
        return true;
    }

    return false;
}
function trim(c) {
    var ctx = c.getContext('2d'),
        copy = document.createElement('canvas').getContext('2d'),
        pixels = ctx.getImageData(0, 0, c.width, c.height),
        l = pixels.data.length,
        i,
        bound = {
            top: null,
            left: null,
            right: null,
            bottom: null
        },
        x, y;
    for (i = 0; i < l; i += 4) {
        if (pixels.data[i + 3] !== 0) {
            x = (i / 4) % c.width;
            y = ~~((i / 4) / c.width);
            if (bound.top === null) {
                bound.top = y;
            }
            if (bound.left === null) {
                bound.left = x;
            } else if (x < bound.left) {
                bound.left = x;
            }
            if (bound.right === null) {
                bound.right = x;
            } else if (bound.right < x) {
                bound.right = x;
            }
            if (bound.bottom === null) {
                bound.bottom = y;
            } else if (bound.bottom < y) {
                bound.bottom = y;
            }
        }
    }
    var trimHeight = bound.bottom - bound.top,
        trimWidth = bound.right - bound.left,
        trimmed = ctx.getImageData(bound.left, bound.top, trimWidth, trimHeight);
    copy.canvas.width = trimWidth;
    copy.canvas.height = trimHeight;
    copy.putImageData(trimmed, 0, 0);
    // open new window with trimmed image:
    return copy.canvas;
}


function setVoice(text_voice) {
    available_voices = window.speechSynthesis.getVoices();
    var utter = new SpeechSynthesisUtterance();
    utter.text = text_voice;
    utter.rate = 0.8;
    utter.pitch = 0.5;
    utter.lang = 'en-US';
    /*utter.voice = available_voices[10];*/
    synth.speak(utter);
}

function set_time_now() {
    let time_now = new Date();
    let options = {
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit"
    };
    var time_now2 = time_now.toLocaleTimeString("id", options);
    time_now2 = time_now2.split(".").join(":");
    return time_now2;
}


function check_is_attendance_hour() {

      var status_type_attendance = "";
      var time_now = set_time_now();
      if (time_now >= start_work && time_now <= start_work_until) {
          status_type_attendance = "check_in";
      } else if (time_now >= end_work && time_now <= end_work_until) {
          status_type_attendance = "check_out";
      }
      return status_type_attendance;
}
function check_ontime_employee(is_attendance_hour_){
    if(is_already_check_ontime && is_attendance_hour_ == "check_out"){
        is_already_check_ontime = false;
    }
    else if(!is_already_check_ontime && is_attendance_hour_ == "check_in"){
        start_worker_check_ontime();
        worker_check_ontime.postMessage({
            base_url: base_url2
        });
    }
}

        

function set_runningtext() {
    $.ajax({
        url: base_url + 'get_running_text/' + token_device,
        method: 'GET',
        dataType: "json",
        crossOrigin: true,
        success: function(result) {
            var response = result['response'];
            if (response == "success") {
                if(running_text != result['running_text']){
                  running_text = result['running_text'];
                  $("#running_text").text(running_text); 
                }
            }
        },
        error: function(request, status, error) {
            console.log(error);
        }
    });
}

function get_ontime_employee() {

    $.ajax({
        //url: base_url + 'api/get_ontime_employee/',
        url: base_url_api + 'api/api_cross.php',
        method: 'GET',
        dataType: "json",
        crossOrigin: true,
        success: function(result) {
            var response = result['response'];
            if (response == "success") {
                var data = result['data'];
                for(var i=0;i<data.length;i++){
                  var number = i + 1;
                  $("#leaderboard_name" + number).text(data[i].name);
                  $("#leaderboard_division" + number).text(data[i].divisi);
                  $("#leaderboard_time" + number).text(data[i].check_in);
                  $("#leaderboard_img" + number).attr("src","data:image/png;base64, " + data[i].image);
                }
                            
                $("#stylecam").css("display", "none");
                $("#adv_style2").css("display", "none");
                $("#adv_style_leaderboard").css("display", "block");
            }
        },
        error: function(request, status, error) {
            console.log(error);
        }
    });
}


        
  
/*=====================  WORKER FUNCTION =========================*/
/*
*  Here are the workers
*/


function getScriptPath(foo) {
    return window.URL.createObjectURL(new Blob([foo.toString().match(/^\s*function\s*\(\s*\)\s*\{(([\s\S](?!\}$))*[\s\S])/)[1]], {
        type: 'text/javascript'
    }));
}

function stopWorkerVideo() {
    if (worker_video != null) {
        worker_video.terminate();
        worker_video = undefined;
    }
}
function stopWorkerCheckOntime() {
    if (worker_check_ontime != null) {
        worker_check_ontime.terminate();
        worker_check_ontime = undefined;
    }
}

function start_worker_running_text() {
    worker_running_text = new Worker(getScriptPath(function() {
        self.addEventListener('message', async function(e) {
            var base_url = e.data.base_url;
            var timer = e.data.timer;
            var token_device = e.data.token_device;
            setInterval(async function() {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    var result = this.response;
                    self.postMessage(result);
                };
                xhttp.open("GET",base_url + 'get_running_text/' + token_device, true);
                xhttp.send();
            }, timer);

        }, false);
    }));
    worker_running_text.addEventListener('message', function(e) {
        let result = e.data;
        if(isJson(result)){
            result = JSON.parse(result);
            var response = result['response'];
            if (response == "success") {
                //console.log(result['running_text']);
                if(running_text != result['running_text']){
                  running_text = result['running_text'];
                  $("#running_text_adv #running_text").text(running_text); 
                }else if(running_text == ""){
                    $("#running_text_adv #running_text").text("SELAMAT DATANG DI HUTAMAKARYA"); 
                }
            }   
        }
    }, false);

}
function start_worker_office_hour() {
    worker_office_hour = new Worker(getScriptPath(function() {
        self.addEventListener('message', async function(e) {
            var base_url = e.data.base_url;
            var timer = e.data.timer;
            setInterval(async function() {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    var result = this.response;
                    self.postMessage(result);
                };
                xhttp.open("GET", base_url + "api/office_hour", true);
                xhttp.send();
            }, timer);

        }, false);
    }));
    worker_office_hour.addEventListener('message', function(e) {
        let result = e.data;
        if(isJson(result)){    
            result = JSON.parse(result);
            start_work = result.start_office_hour_from;
            start_work_until = result.start_office_hour_until;
            end_work = result.end_office_hour_from;
            end_work_until = result.end_office_hour_until;   
            //console.log("start_work : " + start_work + " ;start_work_until : " + start_work_until + " ; end_work : " + end_work + " end_work_until : " + end_work_until);
        }
    }, false);

}
function start_worker_check_ontime() {
    worker_check_ontime = new Worker(getScriptPath(function() {
        self.addEventListener('message', async function(e) {
            var base_url = e.data.base_url;
            setInterval(async function() {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    var result = this.response;
                    self.postMessage(result);
                };
                xhttp.open("GET", base_url + "api/get_five_ontime_employee", true);
                xhttp.send();
            }, 1000);

        }, false);
    }));
    worker_check_ontime.addEventListener('message', function(e) {
        let result = e.data;
        if(isJson(result)){    
            result = JSON.parse(result);
            get_ontime_employee(result);
        }
    }, false);

}
function start_worker_check_display() {
    worker_check_display = new Worker(getScriptPath(function() {
        function sleep(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        }
        function set_time_now() {
            let time_now = new Date();
            let options = {
                hour: "2-digit",
                minute: "2-digit",
                second: "2-digit"
            };
            var time_now2 = time_now.toLocaleTimeString("id", options);
            time_now2 = time_now2.split(".").join(":");
            return time_now2;
        }

        self.addEventListener('message', async function(e) {
            var base_url = e.data.base_url;
            var token_device = e.data.token_device;
            var timer = e.data.timer;
            setInterval(async function() {
                var time_now = set_time_now();
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    var result = this.response;
                    self.postMessage(result);
                };
                xhttp.open("GET", base_url + "get_display_now" + "/" + token_device + "/" + time_now, true);
                xhttp.send();
            }, timer);

        }, false);
    }));
    worker_check_display.addEventListener('message', function(e) {
        let result = e.data;
        if(result){
            check_display_kios(result);   
        }
    }, false);

}


function start_video_worker() {
    worker_video = new Worker(getScriptPath(function() {

        function sleep(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        }
        self.addEventListener('message', async function(e) {
            var timer = 0;
            var video_duration = e.data.video_duration;
            var pos_carousel = e.data.pos_carousel_now;

            //console.log("video_duration : " + video_duration);
            var myVideoTimer = setInterval(function() {
                timer += 1;
                //console.log("timer run : " +  timer + " , until : "+ video_duration);
                if (timer >= video_duration) {
                    clearInterval(myVideoTimer);
                    self.postMessage("test");
                }
            }, 1000);

        }, false);
    }));
    worker_video.addEventListener('message', function(e) {
        stopWorkerVideo();
        /*console.log(on_change_display);*/
        if(on_change_display){
            $("#carousel-custom").carousel("pause");
        }else{
            $('#carousel-custom').carousel('cycle');   
        }


    }, false);

}

function stopWorker(index_) {
    if (worker[index_] != null) {
        worker[index_].terminate();
        worker[index_] = undefined;
    }
}




        

function check_display_kios(result) {
    if(result && isJson(result)){
      result = JSON.parse(result);
      var response = result.response;
      var style_ = result.style_adv;

       if (response == "any_data") {
          var data = result.data;
          if (style_now != style_) {
              on_change_display = true;
              try{ myvideo.pause() }catch(err){ }
              if (style_ == "style1") {
                $("#adv_style2 #carousel_style").appendTo("#adv_style1");   
                setStyle1(data);
              }else if (style_ == "style2") {
                  setStyle2(data);
              }else if (style_ == "style_leaderboard") {
                  setStyleLeaderboard(data);
              }
              style_now = style_;
          }
      }else{
        if (style_now != style_) {
          on_change_display = true;
          try{ myvideo.pause() }catch(err){ }
          setStyleDefault();
          style_now = style_;
        }
      }  
    }
}

function setStyle1(data) {
    running_text_adv_html = $("#running_text_adv").html();
    on_change_display = false;
    try{ $('#carousel-custom').carousel('pause'); }catch(err){ console.log(err); }
    try{ stopWorkerVideo(); }catch(err){ console.log(err); }
    

    var style2_height_cam = "630";
    var style2_width_cam = "840";
    var style2_height_grid2 = "288";
    var style2_height_grid3 = "960";

    var margin_top_main_wrap = parseInt(style2_height_cam);
    var margin_top_wrap_aegis = parseInt(style2_height_cam) - 40;

    $("#stylecam").css("display", "block");

    $("#capture").css("width", style2_width_cam);
    $("#capture").css("height", style2_height_cam);

    $("#adv_style_leaderboard").css("display", "none");
    $("#capture").css("margin-left", "140px");

    $("#wrap-aegisheader").css("margin-top", margin_top_wrap_aegis);
    $("#face_column2").css("display", "none");
    $("#face_column3").css("display", "none");
    $("#running_text_adv").css("display","none");
    $("#adv_style1").css("display", "block");
    $("#main-wrap").css("margin-top", margin_top_main_wrap + "px");
    $("#adv_style1").css("height", style2_height_grid3 + "px");
    
    var html_item_carousel = "";
    var html_thumbnail_carousel = "";
    var active_item = "active";
    var video_item = [];
    var video_index = 0;
    length_carousel = data.length;
    media_type = [];

    for (var i = 0; i < data.length; i++) {
        var source = data[i].advertisement.source;
        var thumbnail = data[i].advertisement.thumbnail;
        var src_item = base_src_uploads + "/" + source;
        var element_ = "";
        var media_type_ = data[i].advertisement.type;

        video_duration[i] = 0;
        media_type.push(data[i].advertisement.type);
        
        if (media_type_ == "Video") {
            element_ = '<video class="video_adv" id="video_' + i + '"><source src="' + src_item + '" type="video/mp4"></video>';
            src_item = base_src_uploads + "/" + thumbnail;
            element2_ = '<img src="' + src_item + '" alt="..." class="img-responsive">';
            video_item[video_index] = i;
            video_index++;
        } else if (media_type_ == "Image") {
            element_ = '<img src="' + src_item + '" alt="..." class="img-responsive">';
            element2_ = '<img src="' + src_item + '" alt="..." class="img-responsive">';
        }

        html_item_carousel += '<div class="item ' + active_item + '" data-id="' + i + '">' + element_ + '</div>';
        //html_thumbnail_carousel += '<li data-target="#carousel-custom" data-slide-to="' + i + '" class="thumbnail_data ' + active_item + '">' + element2_ + '</li>';
        active_item = '';
    }

    $("#wrap_item_carousel").html(html_item_carousel);
    $("#wrap_thumbnail_carousel").html(html_thumbnail_carousel);
    video_item.forEach(function(item){
        var myvideo = $("#video_" + item)[0];
        myvideo.onloadedmetadata = function() {
          video_duration[item] = myvideo.duration;
        };
    });

    $("#wrap_item_carousel").html(html_item_carousel);
    $("#wrap_thumbnail_carousel").html(html_thumbnail_carousel);

    $('#carousel-custom').carousel('cycle');
}


var wrap_default_top = $("#wrap-aegisheader").css("margin-top");
var face_column2_default_display = $("#face_column2").css("display");
var face_column3_default_display = $("#face_column3").css("display");
var adv_style1_default_display = $("#adv_style1").css("display");
var main_default_top = $("#main-wrap").css("margin-top");
var adv_style1_default_height = $("#adv_style1").css("height");
var running_text_adv_html = $("#running_text_adv").html();

var capture_width = $("#capture").css("width");
var capture_height = $("#capture").css("height");
function setStyleDefault() {

        try{ $('#carousel-custom').carousel('pause'); }catch(err){ console.log(err); }
        $("#adv_style_leaderboard").css("display", "none");
        $("#adv_style2").css("display", "none");
        $("#stylecam").css("display", "block");

        $("#capture").css("width", "1080px");
        $("#capture").css("height", "810px");

        $("#wrap-aegisheader").css("margin-top", wrap_default_top);
        $("#face_column2").css("display", face_column2_default_display);
        $("#face_column3").css("display", face_column3_default_display);

        $("#capture").css("margin-left", "0px");
        $("#running_text_adv").show();
        $("#running_text_adv").html(running_text_adv_html);
        $("#running_text_adv #running_text").text(running_text);

        $("#adv_style1").css("display", adv_style1_default_display);
        $("#main-wrap").css("margin-top", main_default_top);
        $("#adv_style1").css("height", adv_style1_default_height);
        var html_item_carousel = "";
        var html_thumbnail_carousel = "";
        var active_item = "active";


}
function setStyleLeaderboard(){
    try{ $('#carousel-custom').carousel('pause'); }catch(err){ console.log(err); }
    get_ontime_employee();

}
function setStyle2(data) {
    on_change_display = false;
    try{ $('#carousel-custom').carousel('pause'); }catch(err){ console.log(err); }
    try{ stopWorkerVideo(); }catch(err){ console.log(err); }

    $("#adv_style_leaderboard").css("display", "none");
    $("#stylecam").css("display", "none");
    $("#adv_style2").css("display", "block");

    var html_item_carousel = "";
    var html_thumbnail_carousel = "";
    var active_item = "active";
    var video_item = [];
    var video_index = 0;
    length_carousel = data.length;
    media_type = [];

    for (var i = 0; i < data.length; i++) {
        var source = data[i].advertisement.source;
        var thumbnail = data[i].advertisement.thumbnail;
        var src_item = base_src_uploads + "/" + source;
        var element_ = "";
        var media_type_ = data[i].advertisement.type;

        video_duration[i] = 0;
        media_type.push(data[i].advertisement.type);
        
        if (media_type_ == "Video") {
            element_ = '<video class="video_adv" id="video_' + i + '"><source src="' + src_item + '" type="video/mp4"></video>';
            src_item = base_src_uploads + "/" + thumbnail;
            element2_ = '<img src="' + src_item + '" alt="..." class="img-responsive">';
            video_item[video_index] = i;
            video_index++;
        } else if (media_type_ == "Image") {
            element_ = '<img src="' + src_item + '" alt="..." class="img-responsive">';
            element2_ = '<img src="' + src_item + '" alt="..." class="img-responsive">';
        }

        html_item_carousel += '<div class="item ' + active_item + '" data-id="' + i + '">' + element_ + '</div>';
        //html_thumbnail_carousel += '<li data-target="#carousel-custom" data-slide-to="' + i + '" class="thumbnail_data ' + active_item + '">' + element2_ + '</li>';
        active_item = '';
    }

    $("#wrap_item_carousel").html(html_item_carousel);
    $("#wrap_thumbnail_carousel").html(html_thumbnail_carousel);
    video_item.forEach(function(item){
        var myvideo = $("#video_" + item)[0];
        myvideo.onloadedmetadata = function() {
          video_duration[item] = myvideo.duration;
        };
    });

    $('#carousel-custom').carousel('cycle');
    $("#carousel_style").appendTo("#adv_style2");
}


        
$("#carousel-custom").on("slide.bs.carousel", async function(a) {
    
    if (on_video_run) {
        stopWorkerVideo();
        on_video_run = false;
        $("#carousel-custom").carousel("cycle");
    }
    if (!onclick_thumbnail) {
        var b = $("#carousel-custom .active").index("#carousel-custom .item");
        pos_carousel_now = b + 1;
        if (pos_carousel_now >= length_carousel) pos_carousel_now = 0;
    }
    onclick_thumbnail = false;
    if ("Video" == media_type[pos_carousel_now]) {
        setTimeout(function() {
            $("#carousel-custom").carousel("pause");
        }, 1);
        myvideo = $("#video_" + pos_carousel_now)[0];
 
        var duration_ = video_duration[pos_carousel_now];
        
        await myvideo.pause();
        myvideo.currentTime = 0;
        await myvideo.play();
        console.log("Video duration : " +  duration_);
        start_video_worker();
        on_video_run = true;
        worker_video.postMessage({
            video_duration: duration_,
            pos_carousel_now: pos_carousel_now
        });
    } else {
        console.log("cycle");
        $("#carousel-custom").carousel("cycle");
    }
});

$(".carousel-inner").bind(".video_adv", "stop", function(a) {
    console.log("stopped");
    /*alert("stopped");*/
});

$(".carousel-indicators").on("click", ".thumbnail_data", function() {
    var a = $(this).attr("data-slide-to");
    pos_carousel_now = a;
    onclick_thumbnail = true;
});

        

    setTimeout(function() {
        $('#carousel-custom').carousel('pause');
    }, 1);
  
    $(document).ready(function() {
        var count = "0";
        function doRealtimeImage() {
          $("#capture").attr("src",kiosk_asset_url + "public/image/capture.jpg?rand=" + Math.random());
        }

        function set_data_cctv() {
            $.ajax({
              url: base_url_api + 'api/face_data/data.php?count=' + count,
              method:'GET',
              dataType:"json", 
              crossOrigin: true,
              success: function(result){
                console.log("phpmyadmin result : ");
                console.log(result);
                count = result['count'];
                var data = result['data'];
                for (var i = 0; i < data.length; i++) {
                  var employee_id = data[i].employee_id;
                  var created_at = data[i].created_at;
                  var time = created_at.slice(10,16);

                  get_face_album_employee(i,time,employee_id);
                }
              },error: function(request, status, error) {
                console.log(error);
              }
            });

        }
        function get_face_album_employee(index_,time,employee_id) {

            $.ajax({
              url: base_url + 'api/get_face_album_employee/' + employee_id,
              method:'GET',
              dataType:"json", 
              crossOrigin: true,
              success: function(result){

                console.log("odoo result : ");
                console.log(result);
                
                var data = result['data'];
                var currentdate = new Date(); 
                var src_image = "data:image/jpeg;base64," + data.image;
                var index = index_;
                $("#img_profile" + index).attr('src',src_image).bind('onreadystatechange load', function(){
                    $("#label_name" + index).text(data.name);
                    $("#label_time" + index).text(time);
                    $("#label_divisi" + index).text(data.divisi_id[1]);
                });

                
                /*console.log(result);*/
              },error: function(request, status, error) {
                console.log(error);
              }
            });

        }
        

        var intervalImage = setInterval(function() {doRealtimeImage();},100);
        var intervalData = setInterval(function() {set_data_cctv();},1000);
        /*WORKER*/
        start_worker_office_hour();
        worker_office_hour.postMessage({
            base_url: base_url,
            timer:timer_office,
        });

        /*start_worker_check_train_data();
        worker_check_data_train.postMessage({
            count_data_training: count_data_training,
            base_url: base_url,
            timer:timer_train_data,
        });*/

        start_worker_running_text();
        worker_running_text.postMessage({
            token_device: token_device,
            base_url: base_url,
            timer:timer_running_text,
        });

        start_worker_check_display();
        worker_check_display.postMessage({
            token_device: token_device,
            base_url: base_url,
            timer:timer_display,
        });
    })


    </script>
</body>
</html>
