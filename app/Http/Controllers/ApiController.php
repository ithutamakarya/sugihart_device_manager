<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use DB;
use Response;

class ApiController extends Controller
{
   
    public function getDataCCTV($token)
    {

        $vendor = DB::table('vendor')->where('token',$token)->first();
        if($vendor){
            $data = DB::table('device')->where('type','cctv')->where('vendor_id',$vendor->id)->get();
            foreach ($data as $key => $value) {
                $data[$key]->url_device = str_replace('/',"_",$value->url_device); 
            }
            return Response::json($data,200);   
        }else{
            return Response::json(["response" => "failed"],200);                  
        } 
    }
    public function getKios()
    {
        $access_token = request()->header('Authorization');
        $ip_access = $_SERVER['REMOTE_ADDR'];

        print_r("ip : ".$access_token);
        print_r("ip : ".$ip_access);
        $data = DB::table('device')->where('type','kios-k')->where('token',$access_token)->where('ip_access',$ip_access)->first();
        if($data){
            return Response::json($data,200);      
        }else{
            return Response::json(["Response" => 'failed'],200);      
        }
    }

    public function getDevice($token)
    {
        $vendor = DB::table('vendor')->where('token',$token)->first();
        if($vendor){
            $data = DB::table('device')->where('vendor_id',$vendor->id)->get();
            return Response::json($data,200);      
        }else{
            return Response::json(["response" => "failed"],200);                  
        }
    }
    public function render_kios(){
        
    }
    


    

}
