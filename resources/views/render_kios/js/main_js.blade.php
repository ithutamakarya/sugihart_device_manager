
    setTimeout(function() {
        $('#carousel-custom').carousel('pause');
    }, 1);
  
    $(document).ready(function() {
        var count = "0";
        function doRealtimeImage() {
          $("#capture").attr("src","public/image/capture.jpg?rand=" + Math.random());
        }

        function set_data_cctv() {
            $.ajax({
              url: base_url_api + 'api/face_data/data.php?count=' + count,
              method:'GET',
              dataType:"json", 
              crossOrigin: true,
              success: function(result){
                count = result['count'];
                var data = result['data'];
                console.log(data);
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
