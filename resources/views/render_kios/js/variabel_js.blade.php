let forwardTimes = [];
var def_image = "image/placeholder-profile-sq.jpg";

const base_url = "http://192.168.12.23/";
const base_url2 = "http://192.168.12.23/";
/*
const base_url = "http://192.168.0.76/hutamakarya/hutamakarya-core/public/";
const base_url2 = "http://192.168.0.76/hutamakarya/hutamakarya-core/public/";*/

const webcam_video = $('#inputVideo').get(0)

let width_image = $("#img_profile0").width();
let height_image = $("#img_profile0").height();
/*attendance variabel*/
let facedata_in = [];
let facedata_out = [];
/*===============*/
/*parameter*/
let maxDescriptorDistance = 0.48;
let max_cap_rec = 2;
let max_recog_time_worker = 3000;
/*=============*/
/*WORKER*/
let face_data_waiting = ["-", "-", "-", "-", "-", "-","-", "-", "-"];
let face_data = ["-", "-", "-", "-", "-", "-","-", "-", "-"];
let face_recog_valid_ = [0, 0, 0, 0, 0, 0, 0, 0, 0];
let url_capture_waiting = ["-", "-", "-", "-", "-", "-", "-", "-", "-"];
let url_capture = ["-", "-", "-", "-", "-", "-", "-", "-", "-"];

let first_recog = false;

var worker_run = ["-", "-", "-", "-", "-", "-", "-", "-", "-"];
var worker = [];
var worker_check_display;
var worker_check_ontime;
var worker_office_hour;
var worker_running_text;

var available_voices;
var synth = window.speechSynthesis;

var is_attendance_hour = "";

/*===========================*/
/*const options = new faceapi.SsdMobilenetv1Options()*/
/*const options = new faceapi.MtcnnOptions()*/

// example
/*const options = new faceapi.TinyFaceDetectorOptions({ inputSize: 320 })
 */
const options = new faceapi.SsdMobilenetv1Options();
let labeledFaceDescriptors;

let labels = [];
let nama = [];
let gender = [];
let department = [];
let nik = [];
let token_employee = [];
let id = [];
let face_pic_profile = [];

let start_work = "00:00";
let start_work_until = "00:00";
let end_work = "00:00";
let end_work_until = "00:00";


var style_now = "default";
/*function reinit_facecam_recognition(){
  video.start();
  $("#stylecam").css("display","block");
}*/

const base_src_uploads = base_url2 + "uploads";
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
var running_text = "";