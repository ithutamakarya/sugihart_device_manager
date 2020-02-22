
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
        url: base_url + 'api/get_ontime_employee/',
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
