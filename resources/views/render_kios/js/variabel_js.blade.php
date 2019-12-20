let forwardTimes = [];
const rtsp_url = "rtsp://admin:adminsdi123@192.168.0.245:554/Streaming/Channels/101/";
var def_image = "https://lakewangaryschool.sa.edu.au/wp-content/uploads/2017/11/placeholder-profile-sq.jpg";
const base_url = "http://hutamakarya.sugihart.com/";
const base_url2 = "http://localhost:9000/";

const video = $('#inputVideo').get(0)

let width_image = $("#img_profile0").width();
let height_image = $("#img_profile0").height();
let data_face = [];
let worker_recog1 = "";
let on_loop_recog1 = "";
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
let face_data_waiting = ["-", "-", "-", "-", "-", "-", "-", "-", "-"];
let face_data = ["-", "-", "-", "-", "-", "-", "-", "-", "-"];
let face_recog_valid_ = [0, 0, 0, 0, 0, 0, 0, 0, 0];
let url_capture_waiting = ["-", "-", "-", "-", "-", "-", "-", "-", "-"];
let url_capture = ["-", "-", "-", "-", "-", "-", "-", "-", "-"];

let first_recog = false;

var worker_run = ["-", "-", "-", "-", "-", "-", "-", "-", "-"];
var worker = [];

var available_voices;
var synth = window.speechSynthesis;
/*===========================*/
let facerecog_on = true;
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
let id = [];
let face_album_detail = [];

let face_pic_profile = [];

let start_work = "08:00";
let start_until_work = "09:00";
let end_work = "17:00";
let end_until_work = "18:00";


var style_now = "default";
/*function reinit_facecam_recognition(){
  video.start();
  $("#stylecam").css("display","block");
}*/

const base_src_uploads = base_url2 + "uploads";
var style_now = "";
var length_carousel = 0;
var media_type = [];



var pos_carousel_now = 1;
var onclick_thumbnail = false;
var on_video_run = false;

var worker_video;
let count_data_training = 0;