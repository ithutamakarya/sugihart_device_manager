  
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
    }))
    worker_running_text.addEventListener('message', function(e) {
        let result = e.data;
        if(isJson(result)){
            result = JSON.parse(result);
            var response = result['response'];
            if (response == "success") {
                if(running_text != result['running_text']){
                  var running_text = result['running_text'];
                  $("#running_text").text(running_text); 
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
    }))
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
    }))
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
    }))
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
                if (timer >= video_duration) {
                    clearInterval(myVideoTimer);

                    self.postMessage("test");
                }
            }, 1000);

        }, false);
    }))
    worker_video.addEventListener('message', function(e) {
        stopWorkerVideo();
        if(on_cam_play){
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



//Worker 1
function startWorker(index_) {
    if (typeof(Worker) !== "undefined") {
        if (index_ == 0) {
            //console.log("run worker " + index_)
            worker[0] = new Worker(getScriptPath(function() {

                function sleep(ms) {
                    return new Promise(resolve => setTimeout(resolve, ms));
                }
                self.addEventListener('message', async function(e) {
                    let timer = 0;
                    let max_recog_time_worker = e.data.max_recog_time;
                    let name = e.data.name;

                    while (timer <= max_recog_time_worker) {
                        if (timer >= max_recog_time_worker) {
                            self.postMessage(name);
                        }

                        timer += 1000;
                        await sleep(1000);
                    }

                }, false);
            }));
            //We add a listener to the worker to get the response and show it in the page
            //trigger from worker js
            worker[0].addEventListener('message', function(e) {
                let name_ = e.data;
                let index_face_waiting_data_waiting = face_data_waiting.indexOf(name_);
                if (face_recog_valid_[index_face_waiting_data_waiting] < max_cap_rec) {
                    face_recog_valid_[index_face_waiting_data_waiting] = 0;
                    url_capture_waiting[index_face_waiting_data_waiting] = "-";
                    face_data_waiting[index_face_waiting_data_waiting] = "-";
                    worker_run[0] = "-"
                }
                stopWorker(0);

            }, false);

        }

        if (index_ == 1) {
            //console.log("run worker " + index_)
            worker[1] = new Worker(getScriptPath(function() {

                function sleep(ms) {
                    return new Promise(resolve => setTimeout(resolve, ms));
                }
                self.addEventListener('message', async function(e) {
                    let timer = 0;
                    let max_recog_time_worker = e.data.max_recog_time;
                    let name = e.data.name;

                    while (timer <= max_recog_time_worker) {
                        if (timer >= max_recog_time_worker) {
                            self.postMessage(name);
                        }

                        timer += 1000;
                        await sleep(1000);
                    }

                }, false);
            }));
            //We add a listener to the worker to get the response and show it in the page
            //trigger from worker js
            worker[1].addEventListener('message', function(e) {
                let name_ = e.data;
                let index_face_waiting_data_waiting = face_data_waiting.indexOf(name_);
                if (face_recog_valid_[index_face_waiting_data_waiting] < max_cap_rec) {
                    face_recog_valid_[index_face_waiting_data_waiting] = 0;
                    url_capture_waiting[index_face_waiting_data_waiting] = "-";
                    face_data_waiting[index_face_waiting_data_waiting] = "-";
                    worker_run[1] = "-"
                }
                stopWorker(1);

            }, false);

        }

        if (index_ == 2) {
            //console.log("run worker " + index_)
            worker[2] = new Worker(getScriptPath(function() {

                function sleep(ms) {
                    return new Promise(resolve => setTimeout(resolve, ms));
                }
                self.addEventListener('message', async function(e) {
                    let timer = 0;
                    let max_recog_time_worker = e.data.max_recog_time;
                    let name = e.data.name;

                    while (timer <= max_recog_time_worker) {
                        if (timer >= max_recog_time_worker) {
                            self.postMessage(name);
                        }

                        timer += 1000;
                        await sleep(1000);
                    }

                }, false);
            }));
            //We add a listener to the worker to get the response and show it in the page
            //trigger from worker js
            worker[2].addEventListener('message', function(e) {
                let name_ = e.data;
                let index_face_waiting_data_waiting = face_data_waiting.indexOf(name_);
                if (face_recog_valid_[index_face_waiting_data_waiting] < max_cap_rec) {
                    face_recog_valid_[index_face_waiting_data_waiting] = 0;
                    url_capture_waiting[index_face_waiting_data_waiting] = "-";
                    face_data_waiting[index_face_waiting_data_waiting] = "-";
                    worker_run[2] = "-"
                }
                stopWorker(2);

            }, false);

        }

        if (index_ == 3) {
            //console.log("run worker " + index_)
            worker[3] = new Worker(getScriptPath(function() {

                function sleep(ms) {
                    return new Promise(resolve => setTimeout(resolve, ms));
                }
                self.addEventListener('message', async function(e) {
                    let timer = 0;
                    let max_recog_time_worker = e.data.max_recog_time;
                    let name = e.data.name;

                    while (timer <= max_recog_time_worker) {
                        if (timer >= max_recog_time_worker) {
                            self.postMessage(name);
                        }

                        timer += 1000;
                        await sleep(1000);
                    }

                }, false);
            }));
            //We add a listener to the worker to get the response and show it in the page
            //trigger from worker js
            worker[3].addEventListener('message', function(e) {
                let name_ = e.data;
                let index_face_waiting_data_waiting = face_data_waiting.indexOf(name_);
                if (face_recog_valid_[index_face_waiting_data_waiting] < max_cap_rec) {
                    face_recog_valid_[index_face_waiting_data_waiting] = 0;
                    url_capture_waiting[index_face_waiting_data_waiting] = "-";
                    face_data_waiting[index_face_waiting_data_waiting] = "-";
                    worker_run[3] = "-"
                }
                stopWorker(3);

            }, false);

        }

        if (index_ == 4) {
            //console.log("run worker " + index_)
            worker[4] = new Worker(getScriptPath(function() {

                function sleep(ms) {
                    return new Promise(resolve => setTimeout(resolve, ms));
                }
                self.addEventListener('message', async function(e) {
                    let timer = 0;
                    let max_recog_time_worker = e.data.max_recog_time;
                    let name = e.data.name;

                    while (timer <= max_recog_time_worker) {
                        if (timer >= max_recog_time_worker) {
                            self.postMessage(name);
                        }

                        timer += 1000;
                        await sleep(1000);
                    }

                }, false);
            }));
            //We add a listener to the worker to get the response and show it in the page
            //trigger from worker js
            worker[4].addEventListener('message', function(e) {
                let name_ = e.data;
                let index_face_waiting_data_waiting = face_data_waiting.indexOf(name_);
                if (face_recog_valid_[index_face_waiting_data_waiting] < max_cap_rec) {
                    face_recog_valid_[index_face_waiting_data_waiting] = 0;
                    url_capture_waiting[index_face_waiting_data_waiting] = "-";
                    face_data_waiting[index_face_waiting_data_waiting] = "-";
                    worker_run[4] = "-"
                }
                stopWorker(4);

            }, false);

        }

        if (index_ == 5) {
            //console.log("run worker " + index_)
            worker[5] = new Worker(getScriptPath(function() {

                function sleep(ms) {
                    return new Promise(resolve => setTimeout(resolve, ms));
                }
                self.addEventListener('message', async function(e) {
                    let timer = 0;
                    let max_recog_time_worker = e.data.max_recog_time;
                    let name = e.data.name;

                    while (timer <= max_recog_time_worker) {
                        if (timer >= max_recog_time_worker) {
                            self.postMessage(name);
                        }

                        timer += 1000;
                        await sleep(1000);
                    }

                }, false);
            }));
            //We add a listener to the worker to get the response and show it in the page
            //trigger from worker js
            worker[5].addEventListener('message', function(e) {
                let name_ = e.data;
                let index_face_waiting_data_waiting = face_data_waiting.indexOf(name_);
                if (face_recog_valid_[index_face_waiting_data_waiting] < max_cap_rec) {
                    face_recog_valid_[index_face_waiting_data_waiting] = 0;
                    url_capture_waiting[index_face_waiting_data_waiting] = "-";
                    face_data_waiting[index_face_waiting_data_waiting] = "-";
                    worker_run[5] = "-"
                }
                stopWorker(5);

            }, false);

        }


    }
}