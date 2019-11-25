<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

use App\User;
use App\Device;
use App\Vendor;
class MainActionController extends BaseCoreController
{
    public function user_create(Request $request){
        $data = new User();
        
        $data->name = $request->name;
        $data->email = $request->email;
        $data->password =  Hash::make($request->password);
        $data->save();
        $this->CreateLogActivity("Simpan","User");
        $this->setMessageCRUD("Simpan","success");
        return Redirect::to('main/user_list');
    }

    public function user_update(Request $request){
        $data = User::where('id', $request->id)->first();
        
        $data->name = $request->name;
        $data->email = $request->email;        
        if($request->password != "terlalu"){
            $data->password =  Hash::make($request->password);
        }
        $data->save();
        $this->CreateLogActivity("Update","User");
        $this->setMessageCRUD("Ubah","success");
        
        return Redirect::to('main/user_list');
    }

    public function user_delete($id){
        $data = User::where('id', $id)->first();
        $data->is_active = 0;
        $data->update();
        $this->CreateLogActivity("Delete","User");
        $this->setMessageCRUD("Hapus","success");
        return Redirect::to('main/user_list');
    }
    
    public function device_create(Request $request){
        $data = new Device();
        
        $data->name = $request->name;
        $data->url_device = $request->url_device;
        $data->type = $request->type;
        $vendor_id = $request->vendor_id;
        $data->ip_access = $request->ip_access;
        $data->token = $request->token;
        $data->save();
        $this->CreateLogActivity("Simpan","Device");
        $this->setMessageCRUD("Simpan","success");
        return Redirect::to('main/device/'.$vendor_id);
    }

    public function device_update(Request $request){
        $data = Device::where('id', $request->id)->first();
        
        $data->name = $request->name;
        $data->url_device = $request->url_device;
        $data->type = $request->type;
        $vendor_id = $request->vendor_id;
        $data->token = $request->token;
        $data->ip_access = $request->ip_access;
        $data->save();
        
        $this->CreateLogActivity("Update","Device");
        $this->setMessageCRUD("Ubah","success");
        
        return Redirect::to('main/device/'.$vendor_id);
    }

    public function device_delete($id,$vendor_id){
        $data = Device::where('id', $id)->first();
        $data->is_active = 0;
        $data->update();
        $this->CreateLogActivity("Delete","Device");
        $this->setMessageCRUD("Hapus","success");
        return Redirect::to('main/device/'.$vendor_id);
    }
   public function vendor_create(Request $request){
        $data = new vendor();
        
        $data->name = $request->name;
        $data->token = $request->token;
        $data->save();
        $this->CreateLogActivity("Simpan","vendor");
        $this->setMessageCRUD("Simpan","success");
        return Redirect::to('main/vendor');
    }

    public function vendor_update(Request $request){
        $data = Vendor::where('id', $request->id)->first();
        
        $data->name = $request->name;
        $data->token = $request->token;
        $data->save();
        
        $this->CreateLogActivity("Update","vendor");
        $this->setMessageCRUD("Ubah","success");
        
        return Redirect::to('main/vendor');
    }

    public function vendor_delete($id){
        $data = Vendor::where('id', $id)->first();
        $data->is_active = 0;
        $data->update();
        $this->CreateLogActivity("Delete","vendor");
        $this->setMessageCRUD("Hapus","success");
        return Redirect::to('main/vendor');
    }
    
}
