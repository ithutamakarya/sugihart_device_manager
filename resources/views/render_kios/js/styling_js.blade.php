function setStyle1(data) {
    running_text_adv_html = $("#running_text_adv").html();

    var style2_height_cam = "630";
    var style2_width_cam = "840";
    var style2_height_grid2 = "288";
    var style2_height_grid3 = "960";

    var margin_top_main_wrap = parseInt(style2_height_cam);
    var margin_top_wrap_aegis = parseInt(style2_height_cam) - 40;

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
    length_carousel = data.length;
    media_type = [];

    for (var i = 0; i < data.length; i++) {
        var source = data[i].advertisement.source;
        var thumbnail = data[i].advertisement.thumbnail;
        var src_item = base_src_uploads + "/" + source;
        var element_ = "";
        var media_type_ = data[i].advertisement.type;

        media_type.push(data[i].advertisement.type);
        if (media_type_ == "Video") {
            element_ = '<video class="video_adv" autoplay muted id="video_' + i + '"><source src="' + src_item + '" type="video/mp4"></video>';
            src_item = base_src_uploads + "/" + thumbnail;
            element2_ = '<img src="' + src_item + '" alt="..." class="img-responsive">';
        } else if (media_type_ == "Image") {
            element_ = '<img src="' + src_item + '" alt="..." class="img-responsive">';
            element2_ = '<img src="' + src_item + '" alt="..." class="img-responsive">';
        }

        html_item_carousel += '<div class="item ' + active_item + '" data-id="' + i + '">' + element_ + '</div>';
        html_thumbnail_carousel += '<li data-target="#carousel-custom" data-slide-to="' + i + '" class="thumbnail_data ' + active_item + '">' + element2_ + '</li>';
        active_item = '';
    }

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

    $("#adv_style_leaderboard").css("display", "none");
    $('#carousel-custom').carousel('pause');
    $("#adv_style2").css("display", "none");
    $("#stylecam").css("display", "block");

    $("#capture").css("width", capture_width);
    $("#capture").css("height", capture_height);


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

    $("#stylecam").css("display", "none");
    $("#adv_style2").css("display", "none");
    $("#adv_style_leaderboard").css("display", "block");
    
}
function setStyle2(data) {

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
        html_thumbnail_carousel += '<li data-target="#carousel-custom" data-slide-to="' + i + '" class="thumbnail_data ' + active_item + '">' + element2_ + '</li>';
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
