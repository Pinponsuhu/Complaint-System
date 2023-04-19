<?php

namespace App\Http\Controllers;

use App\Models\Departments;
use App\Models\Lecturer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class HODController extends Controller
{
    public function new_lecturer(Request $request){
        $request->validate([
            'email' => 'required|email',
            'level_assigned' => 'required',
            'staff_id' => 'required'
        ]);

        // $department = Deparment('prefix')
        $me = Lecturer::where('user_id',auth()->user()->id)->first();
        $user = User::create([
            'is_verified' => true,
            'user_type' => "Lecturer",
            'email' => $request->email,
            'password' => Hash::make($request->email . $request->staff_id)
        ]);

        Lecturer::create([
            'user_id' => $user->id,
            'staff_id' => $request->staff_id,
            'level_assigned' => $request->level_assigned,
            'department_id' => $me->department_id,
        ]);

        return back();

    }

    public function lecturers(){
        $users = User::where('user_type','Lecturer')->paginate(16);

        $status =[true,false];

        return view('lecturers',[
            'status' => $status,
            'users' => $users,
        ]);


    }
}
