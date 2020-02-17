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
        var myvideo = $("#video_" + pos_carousel_now)[0];

        var duration_ = video_duration[pos_carousel_now];
        
        await myvideo.pause();
        myvideo.currentTime = 0;
        await myvideo.play();

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
    alert("stopped");
});

$(".carousel-indicators").on("click", ".thumbnail_data", function() {
    var a = $(this).attr("data-slide-to");
    pos_carousel_now = a;
    onclick_thumbnail = true;
});