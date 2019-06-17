<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;

use App\User;

class UserController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return view('admin.user.index', compact('users'));
    }
    //users
    public function create(Request $request){
        $data = new User();
        
        $data->name = $request->name;
        $data->email = $request->email;
        $data->username = $request->email;
        $data->address = $request->address;
        $data->phone = $request->phone;
        $data->password =  Hash::make($request->password);
        $data->save();
        $this->CreateLogActivity("Simpan","User");
        $this->setMessageCRUD("Simpan","success");
        return Redirect::to('admin/user_list');
    }

    public function update(Request $request){
        $data = User::where('id', $request->id)->first();
        
        $data->name = $request->name;
        $data->email = $request->email;
        $data->username = $request->email;
        $data->address = $request->address;
        $data->phone = $request->phone;        
        if($request->password != "terlalu"){
            $data->password =  Hash::make($request->password);
        }
        $data->save();
        $this->CreateLogActivity("Update","User");
        $this->setMessageCRUD("Ubah","success");
        
        return Redirect::to('admin/user_list');
    }

    public function delete($id){
        $data = User::where('id', $id)->first();
        $data->is_active = 0;
        $data->update();
        $this->CreateLogActivity("Delete","User");
        $this->setMessageCRUD("Hapus","success");
        return Redirect::to('admin/user_list');
    }
    public function logout(){
        Auth::logout();
        return Redirect::to('');
    }
    
    public function setMessageCRUD($message,$status){
        session()->flash('message_alert', "Data Berhasil Di ".$message);
        session()->flash('status_alert', $status);    
    }
}
