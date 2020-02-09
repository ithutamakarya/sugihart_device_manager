
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
              success: function(result){
                count = result['count'];
                var data = result['data'];
                for (var i = 0; i < data.length; i++) {
                  var employee_id = data[i].employee_id;
                  get_face_album_employee(i,employee_id);
                }
              },error: function(request, status, error) {
                console.log(error);
              }
            });

        }
        function get_face_album_employee(index_,employee_id) {

            $.ajax({
              url: base_url + 'api/get_face_album_employee/' + employee_id,
              method:'GET',
              dataType:"json", 
              success: function(result){
                var data = result['data'];
                var src_image = base_url + "face_album/" + data.face_filename;
                var index = index_;
                // load the image, and append it to the element id="image-holder"
                /*$("#img_profile" + index)
                .on('load', function() { 
                    $("#label_name" + index).text(data.name);
                    $("#label_nik" + index).text(data.nik);
                    $("#label_department" + index).text(data.department);
                 })
                .on('error', function() { console.log("error loading image"); })
                .attr("src",src_image);*/

                $("#img_profile" + index).fadeOut(300, function(){
                    $(this).attr('src',src_image).bind('onreadystatechange load', function(){
                          if (this.complete) $(this).fadeIn(300);

                      $("#label_name" + index).text(data.name);
                      $("#label_nik" + index).text(data.nik);
                      $("#label_department" + index).text(data.department);
                    });
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
