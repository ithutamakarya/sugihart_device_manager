
async function read_train(data_train) {

    let f = data_train;
    let a = [];
    await f.map((item, index) => {
        let arr = [];
        arr[0] = new Float32Array(Object.keys(item._descriptors[0]).length);
        arr[1] = new Float32Array(Object.keys(item._descriptors[1]).length);
        for (let j = 0; j < 2; j++) {
            for (let i = 0; i < Object.keys(item._descriptors[j]).length; i++) {
                arr[j][i] = item._descriptors[j][i];
            }
        }
        a[index] = new faceapi.LabeledFaceDescriptors(item._label, arr);
    });
    return a;
}
function takeSnapshot(pos, name_, x, y, width_, height_) {
    // buat elemen img
    var img = document.createElement('img');

    var c1 = document.createElement('canvas');
    var ctx1 = c1.getContext('2d');
    c1.width = webcam_video.width;
    c1.height = webcam_video.height;
    ctx1.translate(-x, -y);
    ctx1.drawImage(webcam_video, 0, 0, webcam_video.width, webcam_video.height);

    var c2 = document.createElement('canvas');
    var ctx2 = c2.getContext('2d');
    c2.width = webcam_video.width;
    c2.height = webcam_video.height;
    ctx2.translate(webcam_video.width - width_, webcam_video.height - height_);
    ctx2.drawImage(c1, 0, 0, webcam_video.width, webcam_video.height);

    var faceCanvas = trim(c2);

    var canvas = document.createElement('canvas');
    canvas.width = faceCanvas.width;
    canvas.height = faceCanvas.height;
    var context = canvas.getContext('2d');
    context.drawImage(faceCanvas, 0, 0, faceCanvas.width, faceCanvas.height);
    // render hasil dari canvas ke elemen img
    let url = canvas.toDataURL('image/png');

    let index_face_waiting = face_data_waiting.indexOf(name_);

    if (url_capture_waiting[index_face_waiting] == "-") {
        url_capture_waiting[index_face_waiting] = url;
    }
}

function setMoveFace() {

    face_data[face_data.length - 1] = "-";
    for (var i = face_data.length - 1; i > 0; i--) {
        face_data[i] = face_data[i - 1];
        url_capture[i] = url_capture[i - 1];
        final_matching(i, false);
    }
    face_data[0] = "-";
    url_capture[0] = "-";

    face_data_waiting[face_data_waiting.length - 1] = "-";
    for (var i = face_data_waiting.length - 1; i > 0; i--) {
        face_data_waiting[i] = face_data_waiting[i - 1];
        face_recog_valid_[i] = face_recog_valid_[i - 1];
        url_capture_waiting[i] = url_capture_waiting[i - 1];
    }
    face_data_waiting[0] = "-";
    /*console.log("face data waiting");*/
}


async function setInfoRecog(name_,type_attendance_hour) {
    let index_face_waiting = face_data_waiting.indexOf(name_);

    if (index_face_waiting != -1) {
        face_recog_valid_[index_face_waiting] += 1;
        if (face_recog_valid_[index_face_waiting] == max_cap_rec) {
            let url_capture_ = url_capture_waiting[index_face_waiting];

            if (name_ == "unknown") {
                face_data_waiting[index_face_waiting] = "unknown .";
            }
            if (first_recog) {
                await setMoveFace();
            }
            //set face data
            let index_face = face_data.indexOf("-");
            if (index_face != -1) {
                face_data[index_face] = name_;
                url_capture[index_face] = url_capture_;
            }
            final_matching(index_face, true,type_attendance_hour);
        }
    } else {

        index_face_waiting = face_data_waiting.indexOf("-");
        /*check any data array in face_data in or out*/ 
        let is_any_facedata = 0;
        if(type_attendance_hour == "check_in"){
            is_any_facedata = facedata_in.indexOf(name_);
        }else if(type_attendance_hour == "check_out"){
            is_any_facedata = facedata_out.indexOf(name_);
        }

        if (index_face_waiting == -1) {
            console.log("waiting face recog finish : ")
            console.log(face_data_waiting);
        }else if(is_any_facedata == -1){
            if (!first_recog) {
                index_face_waiting = 0;
            }
            face_recog_valid_[index_face_waiting] = 0;
            url_capture_waiting[index_face_waiting] = "-";
            if (name_ != "unknown") {
                face_data_waiting[index_face_waiting] = name_;
                let index_worker = worker_run.indexOf("-");
                if (index_worker != -1) {
                    worker_run[index_worker] = index_worker;
                    first_recog = true;
                    startWorker(index_worker);
                    worker[index_worker].postMessage({
                        max_recog_time: max_recog_time_worker,
                        name: name_
                    });
                }
            }
        }
    }

}




function final_matching(index_, with_voice,type_attendance = "") {
    /*let name_ = "unknown";*/
    let name_ = "-";
    let nik_ = "-";
    let gender_ = "-";
    let department_ = "-";
    
    let url_cap = url_capture[index_];
    let face_pic_profile2 = url_capture[index_];

    if (face_data[index_] != "unknown" && face_data[index_] != "-") {

        let index_face_ = labels.indexOf(face_data[index_]);
        let voice_gender = "";
        if (gender[index_face_] == "male") {
            voice_gender = "Mr.";
        } else {
            voice_gender = "Mrs.";
        }

        name_ = nama[index_face_];
        nik_ = nik[index_face_];
        gender_ = gender[index_face_];
        department_ = department[index_face_];
        face_pic_profile2 = face_pic_profile[index_face_];

        /*attendance*/
        console.log(type_attendance);

        if (type_attendance == "check_in") {
            let is_any_facedata = facedata_in.indexOf(name_);
            //tidak ada indexnya di face_data in maka
            facedata_out = [];
            if (is_any_facedata == -1) {
                //voice
                if (with_voice) {
                    if(department_ == "Management") {
                        setVoice("welcome " + voice_gender + " " + nama[index_face_]);
                    }
                }
                /*==========*/
                facedata_in.push(name_);
                setAttendance(index_face_, url_cap);
            }

        } else if (type_attendance == "check_out") {

            let is_any_facedata = facedata_out.indexOf(name_);
            //tidak ada indexnya di face_data in maka
            facedata_in = [];
            if (is_any_facedata == -1) {
                //voice
                if (with_voice) {
                    if(department_ == "Management") {
                        setVoice("good bye " + voice_gender + " " + nama[index_face_]);
                    }
                }
                /*==========*/
                facedata_out.push(name_);
                setAttendance(index_face_, url_cap);
            }
        }
        /*=================================*/

    }
    /*if(face_data[index_] == "unknown"){
      face_data[index_] = "unknown ."; 
    }*/
    /*console.log(face_data);*/
    $("#label_name" + index_).text(name_);
    $("#label_nik" + index_).text(nik_);
    $("#label_department" + index_).text(department_);
    if (url_cap == "-") {
        $("#img_profile" + index_).attr("src", def_image);
    } else {
        $("#img_profile" + index_).fadeOut(300, function() {
            $(this).attr('src', face_pic_profile2).bind('onreadystatechange load', function() {
                if (this.complete) $(this).fadeIn(300);
            });
        });

    }

    first_recog = true;

}