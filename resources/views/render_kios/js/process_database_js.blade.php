
function set_runningtext() {
    $.ajax({
        url: base_url + 'get_running_text/' + token_device,
        method: 'GET',
        dataType: "json",
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

function check_display_kios(result) {
    if(result && isJson(result)){
      result = JSON.parse(result);
      var response = result.response;
      var style_ = result.style_adv;

       if (response == "any_data") {
          var data = result.data;
          if (style_now != style_) {
              if (style_ == "style1") {
                  if(!on_cam_play){
                    on_cam_play = true;
                  }
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
          setStyleDefault();
          style_now = style_;
        }
      }  
    }
}
async function get_ontime_employee(result) {
    if(n_employee_ontime >= 5){
      await stopWorkerCheckOntime();
      n_employee_ontime = 0;
    }
    else if(result && isJson(result) && is_attendance_hour == "check_in"){
      result = JSON.parse(result);
      var data = result.data;
      n_employee_ontime = result.count;
      if(n_employee_ontime > 0){
        for(var i=0;i < data.length;i++){
          var name2 = data[i].name;
          var nik2 = data[i].nik;
          var department2 = data[i].department;
          var face_filename2 = data[i].face_filename;
          var face_attach2 = data[i].face_attach;

          var filename1_exp = face_filename2.split(".");
          var img_base64 = "data:image/" + filename1_exp[1] + ";base64," + face_attach2;
          
          var indexing = i + 1;
          
          $("#label_name_ontime_" + indexing).text(name2);
          $("#label_nik_ontime_" + indexing).text(nik2);
          $("#label_department_ontime_" + indexing).text(department2);
          $("#img_profile_ontime_" + indexing).attr("src",img_base64);
        }  
      }
    }
}