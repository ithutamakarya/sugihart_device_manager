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
        return view('user.index', compact('users'));
    }
    public function logout(){
        Auth::logout();
        return Redirect::to('');
    }
    public function create(Request $request){
        $data = new User();
        
        $data->name = $request->name;
        $data->email = $request->email;
        $data->username = $request->email;
        $data->address = $request->address;
        $data->phone = $request->phone;
        
        $data->password =  Hash::make($request->password);
        
        $data->save();

        $this->setMessageCRUD("Simpan","success");

        return Redirect::to('user_list');
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
        
        $this->setMessageCRUD("Ubah","success");
        
        return Redirect::to('user_list');
    }

    public function delete($id){
        $data = User::where('id', $id)->first();
        
        $data->delete();

        $this->setMessageCRUD("Hapus","success");

        return Redirect::to('user_list');
    }
    public function setMessageCRUD($message,$status){
        session()->flash('message_alert', "Data Berhasil Di ".$message);
        session()->flash('status_alert', $status);    
    }
}
