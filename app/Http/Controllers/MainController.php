<?php

namespace App\Http\Controllers;

use Request;

use App\User;
use App\Device;
use App\Vendor;
use App\LogActivity;

class MainController extends Controller
{
    public $dataParse;
    public function __construct()
    {
        $this->middleware('auth');
        $this->dataParse['vendor'] = Vendor::where('is_active',1)->get();
    }
    public function index()
    {
        return view('home',$this->dataParse);
    }
    
    public function device($vendor_id)
    {
        $this->dataParse['vendor_id'] = $vendor_id;
        $this->dataParse['vendor_name'] = Vendor::where('id',$vendor_id)->first()->name;
        $this->dataParse['device'] = Device::where('vendor_id',$vendor_id)->where('is_active',1)->get();
        return view('main.device.index', $this->dataParse);
    }

    public function users()
    {
        $this->dataParse['users'] = User::where('is_active',1)->get();
        return view('main.user.index', $this->dataParse);
    }
    public function log_activity()
    {
        $this->dataParse['log_activity'] = LogActivity::orderBy('id','desc')->get();
        return view('main.log_activity.index', $this->dataParse);
    }



}
