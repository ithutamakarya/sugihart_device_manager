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
            $data = DB::table('device')->where('type','cctv')->where('is_active',1)->where('vendor_id',$vendor->id)->orderBy('token','asc')->orderBy('name','asc')->get();
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
        $data = DB::table('device')->where('type','kios-k')->where('is_active',1)->where('token',$access_token)->where('ip_access',$ip_access)->first();
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
    function get_client_ip()
     {
          $ipaddress = '';
          if (getenv('HTTP_CLIENT_IP'))
              $ipaddress = getenv('HTTP_CLIENT_IP');
          else if(getenv('HTTP_X_FORWARDED_FOR'))
              $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
          else if(getenv('HTTP_X_FORWARDED'))
              $ipaddress = getenv('HTTP_X_FORWARDED');
          else if(getenv('HTTP_FORWARDED_FOR'))
              $ipaddress = getenv('HTTP_FORWARDED_FOR');
          else if(getenv('HTTP_FORWARDED'))
              $ipaddress = getenv('HTTP_FORWARDED');
          else if(getenv('REMOTE_ADDR'))
              $ipaddress = getenv('REMOTE_ADDR');
          else
              $ipaddress = 'UNKNOWN';

          return $ipaddress;
     }
    public function render_kios(){

        $access_token = request()->header('APIKEY');
        $data =[
                'token' => $access_token
        ];

        $ip_access = $this->get_client_ip();
        $data = DB::table('device')->where('type','kios-k')
                                    ->where('is_active',1)
                                    ->where('token',$access_token)
                                    ->where('ip_access',$ip_access)->first();
        print_r($ip_access);
        exit();
        return view('render_kios.index',$data);
    }
    

}
