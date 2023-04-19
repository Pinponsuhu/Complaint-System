<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(){
        return view('auth.login');
    }
    public function register(){
        return view('auth.register');
    }
    public function logining(Request $request){
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        if(!auth()->attempt($request->only('email','password'),$request->remember_me)){
            return back()->with('message','Email or Password Not Correct');
        }

        if(auth()->user()->user_type == 'Student'){
            return redirect('/');
        }else if(auth()->user()->user_type == 'Lecturer'){
            return redirect('/lecturer/dashboard');
        }else{
            return redirect('/admin/dashboard');
        }
    }

    public function verification_view(){
        return view('auth.verify');
    }
}
