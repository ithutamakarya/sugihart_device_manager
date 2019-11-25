<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\Hash;

use App\LogActivity;
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
        $LogActivity = new LogActivity();
        $LogActivity->user_device = Auth::user()->name;
        $LogActivity->activity = "Melakukan ".$value." Data";
        $LogActivity->ip_address = $_SERVER['REMOTE_ADDR'];
        $LogActivity->save();
    }
    
    public function setMessageCRUD($message,$status){
        session()->flash('message_alert', "Data Berhasil Di ".$message);
        session()->flash('status_alert', $status);    
    }
    
    
}
