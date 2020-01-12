
function loadDataTrain() {
      $.ajax({
          url: base_url + 'get_data_train_mysql',
          method: 'GET',
          dataType: "json",
          success: async function(result) {
              let data = result['data_training'];
              count_data_training = result['count_data_training'];
              
              $("#text_loading").text("Training Data...");
              Promise.all([
                  labeledFaceDescriptors = await read_train(data),
                  faceapi.tf.setBackend('webgl')
              ]).then(run)

          },
          error: function(request, status, error) {

          }
      });

}

/*function checkUpdateDataTrain() {
      $.ajax({
          url: base_url + 'check_update_train_mysql/' + count_data_training,
          method: 'GET',
          dataType: "json",
          success: async function(result) {
              let response = result['response'];
              if(response == "any_change"){
                location.reload();
                let data = result['data_training'];
                count_data_training = result['count_data_training'];
                $("#text_loading").text("Training Data...");
                var labeledFaceDescriptors2 = await read_train(data);
                Promise.all([
                    labeledFaceDescriptors.push(labeledFaceDescriptors2),
                ]);

              }
              

          },
          error: function(request, status, error) {

          }
      });

}*/


function loadDataEmployee() {
      $.ajax({
          url: base_url + 'api/get_all_employee2',
          method: 'GET',
          dataType: "json",
          success: async function(result) {
              let data = result;
              for (let i = 0; i < data.length; i++) {
                  id[i] = data[i].id;
                  var slug = data[i].name;
                  labels[i] = slug.replace(" ", "_");
                  nama[i] = data[i].name;
                  nik[i] = data[i].nik;
                  department[i] = "";
                  token_employee[i] = data[i].token;
                  if (Array.isArray(data[i].department_id)) {
                      department[i] = data[i].department_id[1];
                  }
                  gender[i] = data[i].gender;
                  var face_filename1 = data[i].face1_filename;
                  if(face_filename1){
                    var filename1_exp = face_filename1.split(".");
                    face_pic_profile[i] = "data:image/" + filename1_exp[1] + ";base64," + data[i].face1_attach; 
                  }else{
                    face_pic_profile[i] = ""; 

                  }
                  
              }
              $("#text_loading").text("Training Data...");
              loadDataTrain()
              

          },
          error: function(request, status, error) {

          }
      });

  }
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

function setAttendance(indexx_, url_cap_,data_parse,data_voice) {
    var data = {
        id: id[indexx_],
        authorization:token_employee[indexx_],
        capture_image: url_cap_,
        attendance_type: "kios-k",
    }
    console.log(data);
    $.ajax({
        url: base_url + 'api/attendance/set',
        method: 'POST',
        data: data,
        dataType: "json",
        success: function(result) {
            /*console.log("insert");
            console.log(result);
            */
            /*console.log(nama[indexx_] + " = " + response);*/
            var response = result['response'];
            var name_ = data_parse.name;
            var nik_ = data_parse.nik;
            var department_ = data_parse.department;
            var face_pic_profile_ = data_parse.face_pic_profile;
            if(response == "already_attendance" || response == "out_office_hour"){  
                nik_ = result['response'] + " - " + result['attendance_hour']; 
                department_ = "";
            }
            if(response == "success"){
              if(data_voice.is_voice == true){
                setVoice(data_voice.message_voice);
              }
            }

            $("#label_name" + data_parse.index_).text(name_);
            $("#label_nik" + data_parse.index_).text(nik_);
            $("#label_department" + data_parse.index_).text(department_);
            $("#img_profile" + data_parse.index_).attr("src", face_pic_profile_); 
            /*console.log(result);*/
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
                    webcam_video.play();
                  }
                  setStyle1(data);
              }else if (style_ == "style2") {
                  webcam_video.pause();
                  on_cam_play = false;
                  setStyle2(data);
              }
              style_now = style_;
          }
      }else{
        if (style_now != style_) {
          if(!on_cam_play){
            on_cam_play = true;
            webcam_video.play();
          }
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