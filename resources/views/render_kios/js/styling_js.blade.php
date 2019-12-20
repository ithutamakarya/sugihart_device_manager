function setStyle1(data) {
    var style2_height_cam = "630";
    var style2_width_cam = "840";
    var style2_height_grid2 = "288";
    var style2_height_grid3 = "960";

    var margin_top_main_wrap = parseInt(style2_height_cam);
    var margin_top_wrap_aegis = parseInt(style2_height_cam) - 40;

    $("#overlay").attr("height", style2_height_cam);
    $("#inputVideo").attr("height", style2_height_cam);
    $("#overlay").attr("width", style2_width_cam);
    $("#inputVideo").attr("width", style2_width_cam);
    $("#inputVideo").css("margin-left", "140px");
    $("#overlay").css("margin-left", "140px");

    $("#wrap-aegisheader").css("margin-top", margin_top_wrap_aegis);
    $("#face_column2").css("display", "none");
    $("#face_column3").css("display", "none");
    $("#running_text_adv").css("opacity", 0);
    $("#adv_style2").css("display", "block");
    $("#main-wrap").css("margin-top", margin_top_main_wrap + "px");
    $("#adv_style2").css("height", style2_height_grid3 + "px");
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
var overlay_default_height = $("#overlay").attr("height");
var inputvideo_default_height = $("#inputVideo").attr("height");
var overlay_default_width = $("#overlay").attr("width");
var inputvideo_default_width = $("#inputVideo").attr("width");
var overlay_default_left = $("#overlay").css("margin-left");
var inputvideo_default_left = $("#inputVideo").css("margin-left");


var wrap_default_top = $("#wrap-aegisheader").css("margin-top");
var face_column2_default_display = $("#face_column2").css("display");
var face_column3_default_display = $("#face_column3").css("display");
var running_text_adv_default_display = $("#running_text_adv").css("display");
var adv_style2_default_display = $("#adv_style2").css("display");
var main_default_top = $("#main-wrap").css("margin-top");
var adv_style2_default_height = $("#adv_style2").css("height");

function setStyleDefault() {

    $('#carousel-custom').carousel('pause');


    $("#overlay").attr("height", overlay_default_height);
    $("#inputVideo").attr("height", inputvideo_default_height);

    $("#overlay").attr("width", overlay_default_width);
    $("#inputVideo").attr("width", inputvideo_default_width);

    $("#inputVideo").css("margin-left", inputvideo_default_left);
    $("#overlay").css("margin-left", overlay_default_left);

    $("#wrap-aegisheader").css("margin-top", wrap_default_top);
    $("#face_column2").css("display", face_column2_default_display);
    $("#face_column3").css("display", face_column3_default_display);

    $("#running_text_adv").css("opacity", 1);

    $("#adv_style2").css("display", adv_style2_default_display);
    $("#main-wrap").css("margin-top", main_default_top);
    $("#adv_style2").css("height", adv_style2_default_height);
    var html_item_carousel = "";
    var html_thumbnail_carousel = "";
    var active_item = "active";


}