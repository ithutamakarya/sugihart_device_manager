  
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


function start_video_worker() {
    worker_video = new Worker(getScriptPath(function() {

        function sleep(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        }
        self.addEventListener('message', async function(e) {
            var timer = 0;
            var video_duration = e.data.video_duration;
            var pos_carousel = e.data.pos_carousel_now;

            console.log("worker");
            var myVideoTimer = setInterval(function() {
                timer += 1;
                console.log(timer);
                if (timer >= video_duration) {
                    clearInterval(myVideoTimer);
                    self.postMessage("test");
                }
            }, 1000);

        }, false);
    }))
    worker_video.addEventListener('message', function(e) {
        stopWorkerVideo();
        $('#carousel-custom').carousel('cycle');

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

        if (index_ == 6) {
            //console.log("run worker " + index_)
            worker[6] = new Worker(getScriptPath(function() {

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
            worker[6].addEventListener('message', function(e) {
                let name_ = e.data;
                let index_face_waiting_data_waiting = face_data_waiting.indexOf(name_);
                if (face_recog_valid_[index_face_waiting_data_waiting] < max_cap_rec) {
                    face_recog_valid_[index_face_waiting_data_waiting] = 0;
                    url_capture_waiting[index_face_waiting_data_waiting] = "-";
                    face_data_waiting[index_face_waiting_data_waiting] = "-";
                    worker_run[6] = "-"
                }
                stopWorker(6);

            }, false);

        }

        if (index_ == 7) {
            //console.log("run worker " + index_)
            worker[7] = new Worker(getScriptPath(function() {

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
            worker[7].addEventListener('message', function(e) {
                let name_ = e.data;
                let index_face_waiting_data_waiting = face_data_waiting.indexOf(name_);
                if (face_recog_valid_[index_face_waiting_data_waiting] < max_cap_rec) {
                    face_recog_valid_[index_face_waiting_data_waiting] = 0;
                    url_capture_waiting[index_face_waiting_data_waiting] = "-";
                    face_data_waiting[index_face_waiting_data_waiting] = "-";
                    worker_run[7] = "-"
                }
                stopWorker(7);

            }, false);

        }

        if (index_ == 8) {
            //console.log("run worker " + index_)
            worker[8] = new Worker(getScriptPath(function() {

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
            worker[8].addEventListener('message', function(e) {
                let name_ = e.data;
                let index_face_waiting_data_waiting = face_data_waiting.indexOf(name_);
                if (face_recog_valid_[index_face_waiting_data_waiting] < max_cap_rec) {
                    face_recog_valid_[index_face_waiting_data_waiting] = 0;
                    url_capture_waiting[index_face_waiting_data_waiting] = "-";
                    face_data_waiting[index_face_waiting_data_waiting] = "-";
                    worker_run[8] = "-"
                }
                stopWorker(8);

            }, false);

        }

    }
}