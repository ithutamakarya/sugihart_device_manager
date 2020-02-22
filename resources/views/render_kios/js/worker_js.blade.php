  
/*=====================  WORKER FUNCTION =========================*/
/*
*  Here are the workers
*/


function getScriptPath(foo) {
    return window.URL.createObjectURL(new Blob([foo.toString().match(/^\s*function\s*\(\s*\)\s*\{(([\s\S](?!\}$))*[\s\S])/)[1]], {
        type: 'text/javascript'
    }));
}

function stopWorkerVideo() {
    if (worker_video != null) {
        worker_video.terminate();
        worker_video = undefined;
    }
}
function stopWorkerCheckOntime() {
    if (worker_check_ontime != null) {
        worker_check_ontime.terminate();
        worker_check_ontime = undefined;
    }
}

function start_worker_running_text() {
    worker_running_text = new Worker(getScriptPath(function() {
        self.addEventListener('message', async function(e) {
            var base_url = e.data.base_url;
            var timer = e.data.timer;
            var token_device = e.data.token_device;
            setInterval(async function() {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    var result = this.response;
                    self.postMessage(result);
                };
                xhttp.open("GET",base_url + 'get_running_text/' + token_device, true);
                xhttp.send();
            }, timer);

        }, false);
    }));
    worker_running_text.addEventListener('message', function(e) {
        let result = e.data;
        if(isJson(result)){
            result = JSON.parse(result);
            var response = result['response'];
            if (response == "success") {
                //console.log(result['running_text']);
                if(running_text != result['running_text']){
                  running_text = result['running_text'];
                  $("#running_text_adv #running_text").text(running_text); 
                }else if(running_text == ""){
                    $("#running_text_adv #running_text").text("SELAMAT DATANG DI HUTAMAKARYA"); 
                }
            }   
        }
    }, false);

}
function start_worker_office_hour() {
    worker_office_hour = new Worker(getScriptPath(function() {
        self.addEventListener('message', async function(e) {
            var base_url = e.data.base_url;
            var timer = e.data.timer;
            setInterval(async function() {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    var result = this.response;
                    self.postMessage(result);
                };
                xhttp.open("GET", base_url + "api/office_hour", true);
                xhttp.send();
            }, timer);

        }, false);
    }));
    worker_office_hour.addEventListener('message', function(e) {
        let result = e.data;
        if(isJson(result)){    
            result = JSON.parse(result);
            start_work = result.start_office_hour_from;
            start_work_until = result.start_office_hour_until;
            end_work = result.end_office_hour_from;
            end_work_until = result.end_office_hour_until;   
            //console.log("start_work : " + start_work + " ;start_work_until : " + start_work_until + " ; end_work : " + end_work + " end_work_until : " + end_work_until);
        }
    }, false);

}
function start_worker_check_ontime() {
    worker_check_ontime = new Worker(getScriptPath(function() {
        self.addEventListener('message', async function(e) {
            var base_url = e.data.base_url;
            setInterval(async function() {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    var result = this.response;
                    self.postMessage(result);
                };
                xhttp.open("GET", base_url + "api/get_five_ontime_employee", true);
                xhttp.send();
            }, 1000);

        }, false);
    }));
    worker_check_ontime.addEventListener('message', function(e) {
        let result = e.data;
        if(isJson(result)){    
            result = JSON.parse(result);
            get_ontime_employee(result);
        }
    }, false);

}
function start_worker_check_display() {
    worker_check_display = new Worker(getScriptPath(function() {
        function sleep(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
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

        self.addEventListener('message', async function(e) {
            var base_url = e.data.base_url;
            var token_device = e.data.token_device;
            var timer = e.data.timer;
            setInterval(async function() {
                var time_now = set_time_now();
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    var result = this.response;
                    self.postMessage(result);
                };
                xhttp.open("GET", base_url + "get_display_now" + "/" + token_device + "/" + time_now, true);
                xhttp.send();
            }, timer);

        }, false);
    }));
    worker_check_display.addEventListener('message', function(e) {
        let result = e.data;
        if(result){
            check_display_kios(result);   
        }
    }, false);

}


function start_video_worker() {
    worker_video = new Worker(getScriptPath(function() {

        function sleep(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        }
        self.addEventListener('message', async function(e) {
            var timer = 0;
            var video_duration = e.data.video_duration;
            var pos_carousel = e.data.pos_carousel_now;

            //console.log("video_duration : " + video_duration);
            var myVideoTimer = setInterval(function() {
                timer += 1;
                //console.log("timer run : " +  timer + " , until : "+ video_duration);
                if (timer >= video_duration) {
                    clearInterval(myVideoTimer);
                    self.postMessage("test");
                }
            }, 1000);

        }, false);
    }));
    worker_video.addEventListener('message', function(e) {
        stopWorkerVideo();
        /*console.log(on_change_display);*/
        if(on_change_display){
            $("#carousel-custom").carousel("pause");
        }else{
            $('#carousel-custom').carousel('cycle');   
        }


    }, false);

}

function stopWorker(index_) {
    if (worker[index_] != null) {
        worker[index_].terminate();
        worker[index_] = undefined;
    }
}


