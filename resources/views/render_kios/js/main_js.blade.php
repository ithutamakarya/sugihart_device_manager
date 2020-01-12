
 setTimeout(function() {
        $('#carousel-custom').carousel('pause');
    }, 1);

    $(document).ready(function() {
        
        /*console.log("width : " + $(window).width());
        console.log("height : " + $(window).height());*/
        $('#modal_loading').modal('show');
        Promise.all([
            faceapi.nets.faceLandmark68Net.loadFromUri(path_weights),
            faceapi.nets.faceRecognitionNet.loadFromUri(path_weights),
            faceapi.nets.faceExpressionNet.loadFromUri(path_weights),
            faceapi.nets.ssdMobilenetv1.loadFromUri(path_weights),

        ]).then(loadDataEmployee);
    })

    
    async function detect_recognition(type_attendance_hour){
          const ts = Date.now()

          const canvas = $("#overlay").get(0);
          const displaySize = {
              width: webcam_video.width,
              height: webcam_video.height
          }

          const detections = await faceapi.detectAllFaces(webcam_video, options).withFaceLandmarks().withFaceDescriptors()
          //faceapi.matchDimensions(canvas, displaySize)
          const resizedDetections = faceapi.resizeResults(detections, displaySize)
          let faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors, 0.44);
          
          let results = resizedDetections.map(d => faceMatcher.findBestMatch(d.descriptor))
          let res_ = "";

          let width_cap = [];
          let height_cap = [];
          let x_cap = [];
          let y_cap = [];
          /*console.log(faceapi.tf.getBackend());*/

          var ctx = canvas.getContext('2d');
          ctx.clearRect(0,0,webcam_video.width,webcam_video.height);
          results.forEach((result, i) => {

              const box = resizedDetections[i].detection.box;
              /*
              const drawBox = new faceapi.draw.DrawBox(box, {
                  label: result.toString()
              });
              drawBox.draw(canvas);
              */
              let result_string = result.toString();
              if (result_string != "") {
                  res_ = result_string;

                  width_cap[i] = box._width;
                  height_cap[i] = box._height;
                  x_cap[i] = box._x;
                  y_cap[i] = box._y;
                  let res_face = result_string.split(" ");
                  let name_ = res_face[0];
                  let threesold_ = res_face[1];
                    Promise.all([
                        setInfoRecog(name_,type_attendance_hour),
                        takeSnapshot(i, name_, x_cap[i], y_cap[i], width_cap[i], height_cap[i]),
                        
                    ]) 
                    /*checkUpdateDataTrain(count_data_training)*/
              }


          });
    }
    function onPlay() {

      if(webcam_video.paused || webcam_video.ended){

        return setTimeout(() => onPlay()) 
      }
      if(on_cam_play){
        is_attendance_hour = check_is_attendance_hour();
        /*console.log(is_attendance_hour);*/
        /*check_ontime_employee(is_attendance_hour);*/
        if(is_attendance_hour){
          detect_recognition(is_attendance_hour);
        }
      }
      setTimeout(() => onPlay())
      
    }

    async function run() {
      // load face detection model
      /*await changeFaceDetector(SSD_MOBILENETV1)*/
      // try to access users webcam and stream the images
      // to the webcam_video element

      $('#modal_loading').modal('hide');
      start_worker_office_hour();
      worker_office_hour.postMessage({
          base_url: base_url2,
          timer:timer_office,
      });

      start_worker_check_train_data();
      worker_check_data_train.postMessage({
          count_data_training: count_data_training,
          base_url: base_url2,
          timer:timer_train_data,
      });

      start_worker_running_text();
      worker_running_text.postMessage({
          token_device: token_device,
          base_url: base_url2,
          timer:timer_running_text,
      });

      start_worker_check_display();
      worker_check_display.postMessage({
          token_device: token_device,
          base_url: base_url2,
          timer:timer_display,
      });
      const stream = await navigator.mediaDevices.getUserMedia({ video: {} })
      const webcam_video = $('#inputVideo').get(0)
      webcam_video.srcObject = stream
    }