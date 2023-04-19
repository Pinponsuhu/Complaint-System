<?php

namespace App\Http\Controllers;

use App\Mail\EmailVerify;
use App\Mail\PasswordRecovery;
use App\Models\Level;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function index(Request $request){
        $request->validate([
            'email' => 'required|email'
        ]);

        // $token = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(8/strlen($x)) )),1,8);
        $user = User::where('email', $request->email)->first();
        $count = User::where('email', $request->email)->count();

        if($count > 0){
            $check = DB::table('password_resets')->where('email',$request->email)->count();
        if($check != 0){
            DB::table('password_resets')->where('email',$request->email)->delete();
        }
        $token = rand(000000,999999);
        DB::table('password_resets')->insert([
            'email' => strtolower($request->email),
            'token' => $token,
            'created_at' => Carbon::now()
        ]);
            Mail::to($request->email)->send(new PasswordRecovery($user->name, $token));
            return redirect('/reset-password');
        }else{
            return back()->with('msg','Email does not exist');
        }
    }

    public function resetPassword(Request $request){
        $request->validate([
            'pin' => 'required',
            'password' => 'required|confirmed',
        ]);
        DB::table('password_resets')->where('email',$request->email)->delete();
        $user = User::where('email',$request->email)->first();
        $user->password = Hash::make($request->password);
        $user->update();

        return redirect('/login');
    }

    public function registerVerify(Request $request){
        $request->validate([
            'email' => 'required',
            'matric_number' => 'required|size:9',
            'password' => 'required|confirmed',
        ]);
        $name = explode('.', $request->email);
        $check_prefix = $request->matric_number[0] . $request->matric_number[1];
        $levels = Level::where('prefix',$check_prefix)->first();

        if($levels){
            $user = User::create([
                'email' => $request->email,
                'matric_number' => $request->matric_number,
                'user_type' => 'Student',
                'password' => Hash::make($request->password),
            ]);

            Student::create([
                'matric_number' => $request->matric_number,
                'level' => $levels->level,
                'department_id' => $request->matric_number[2] . $request->matric_number[3] . $request->matric_number[4] .$request->matric_number[5],
                'user_id' => $user->id
            ]);

            $token = mt_rand(100000,999999);
            DB::table('password_reset_tokens')->insert([
                'email' => strtolower($request->email),
                'token' => $token,
                'created_at' => Carbon::now()
            ]);
            Mail::to($request->email)->send(new EmailVerify($name[0], $token));

            return redirect('/email/verify');

        }else{
                return back()->with('message',"An error has occurred kindly report to the LASU-ICT");
            }





    }

    public function verifyMail(Request $request){
        $request->validate([
            'email' => 'required|email'
        ]);

        // $token = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(8/strlen($x)) )),1,8);
        $user = User::where('email', $request->email)->first();
        $count = User::where('email', $request->email)->count();

        if($count > 0){
            $check = DB::table('password_resets')->where('email',$request->email)->count();
        if($check != 0){
            DB::table('password_resets')->where('email',$request->email)->delete();
        }
        $token = rand(000000,999999);
        DB::table('password_reset_tokens')->insert([
            'email' => strtolower($request->email),
            'token' => $token,
            'created_at' => Carbon::now()
        ]);
            Mail::to($request->email)->send(new EmailVerify($user->name, $token));
            return redirect('/login');
        }else{
            return back()->with('msg','Email does not exist');
        }


    }

    public function registering(Request $request){
        $request->validate([
            'email'=> 'email|required',
            'pin' => 'required|size:6'
        ]);

        $details = DB::table('password_reset_tokens')->where('email',$request->email)->where('token',$request->pin)->first();
        $details_count = DB::table('password_reset_tokens')->where('email',$request->email)->where('token',$request->pin)->count();

        if($details_count > 0){
            $user = User::where('email',$details->email)->first();
        $user->update([
            'is_active' => true
        ]);
        DB::table('password_reset_tokens')->where('email',$request->email)->where('token',$request->pin)->delete();

        return redirect()->route('login')->with('message',"Profile registered successfully");
        }else{
            return back()->with('message',"Invalid credentials");
        }



    }
}



