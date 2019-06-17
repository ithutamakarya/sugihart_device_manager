<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\Hash;

use App\ActivityLog;
class BaseCoreController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function CreateLogActivity($value,$activity)
    {
        $ActivityLog = new ActivityLog();
        $ActivityLog->user_id = Auth::user()->id;
        $ActivityLog->log_activity = "Melakukan <label class='label label-primary'>".$value."</label> Data";
        $ActivityLog->activity = $activity;
        $ActivityLog->ip_address = $_SERVER['REMOTE_ADDR'];
        $ActivityLog->save();
    }
    
    public function setMessageCRUD($message,$status){
        session()->flash('message_alert', "Data Berhasil Di ".$message);
        session()->flash('status_alert', $status);    
    }
    
}
