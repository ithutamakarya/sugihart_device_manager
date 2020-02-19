let forwardTimes = [];
var def_image = "public/image/placeholder-profile.jpg";

const base_url = "https://absensi.hutamakarya.com/";
const base_url_api = "http://localhost/hutamakarya-kios-live2/";

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
