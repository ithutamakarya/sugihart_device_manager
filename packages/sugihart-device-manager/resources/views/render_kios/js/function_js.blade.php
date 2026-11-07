function isJson(item) {
    item = typeof item !== "string"
        ? JSON.stringify(item)
        : item;

    try {
        item = JSON.parse(item);
    } catch (e) {
        return false;
    }

    if (typeof item === "object" && item !== null) {
        return true;
    }

    return false;
}
function trim(c) {
    var ctx = c.getContext('2d'),
        copy = document.createElement('canvas').getContext('2d'),
        pixels = ctx.getImageData(0, 0, c.width, c.height),
        l = pixels.data.length,
        i,
        bound = {
            top: null,
            left: null,
            right: null,
            bottom: null
        },
        x, y;
    for (i = 0; i < l; i += 4) {
        if (pixels.data[i + 3] !== 0) {
            x = (i / 4) % c.width;
            y = ~~((i / 4) / c.width);
            if (bound.top === null) {
                bound.top = y;
            }
            if (bound.left === null) {
                bound.left = x;
            } else if (x < bound.left) {
                bound.left = x;
            }
            if (bound.right === null) {
                bound.right = x;
            } else if (bound.right < x) {
                bound.right = x;
            }
            if (bound.bottom === null) {
                bound.bottom = y;
            } else if (bound.bottom < y) {
                bound.bottom = y;
            }
        }
    }
    var trimHeight = bound.bottom - bound.top,
        trimWidth = bound.right - bound.left,
        trimmed = ctx.getImageData(bound.left, bound.top, trimWidth, trimHeight);
    copy.canvas.width = trimWidth;
    copy.canvas.height = trimHeight;
    copy.putImageData(trimmed, 0, 0);
    // open new window with trimmed image:
    return copy.canvas;
}


function setVoice(text_voice) {
    available_voices = window.speechSynthesis.getVoices();
    var utter = new SpeechSynthesisUtterance();
    utter.text = text_voice;
    utter.rate = 0.8;
    utter.pitch = 0.5;
    utter.lang = 'en-US';
    /*utter.voice = available_voices[10];*/
    synth.speak(utter);
}

function set_time_now() {
    let time_now = new Date();
    let options = {
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit"
    };
    var time_now2 = time_now.toLocaleTimeString("id", options);
    time_now2 = time_now2.split(".").join(":");
    return time_now2;
}


function check_is_attendance_hour() {

      var status_type_attendance = "";
      var time_now = set_time_now();
      if (time_now >= start_work && time_now <= start_work_until) {
          status_type_attendance = "check_in";
      } else if (time_now >= end_work && time_now <= end_work_until) {
          status_type_attendance = "check_out";
      }
      return status_type_attendance;
}
function check_ontime_employee(is_attendance_hour_){
    if(is_already_check_ontime && is_attendance_hour_ == "check_out"){
        is_already_check_ontime = false;
    }
    else if(!is_already_check_ontime && is_attendance_hour_ == "check_in"){
        start_worker_check_ontime();
        worker_check_ontime.postMessage({
            base_url: base_url2
        });
    }
}