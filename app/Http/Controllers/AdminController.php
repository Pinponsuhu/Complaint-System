<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Departments;
use App\Models\Level;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function add_department(Request $request){
        $request->validate([
            'department_id' => 'required',
            'name' => 'required',
        ]);

        Departments::create([
            'department_id' => $request->department_id,
            'name' => $request->name,
        ]);

        return back();
    }

    public function edit_department(Request $request, $id){
        $request->validate([
            'department_id' => 'required',
            'name' => 'required',
        ]);

        $department = Departments::find($id);

        $department->update([
            'department_id' => $request->department_id,
            'name' => $request->name,
        ]);

        return redirect('/departments');
    }
    public function delete($department_id){
        $department = Departments::find($department_id);
        $department->is_active = !$department->is_active;
        $department->update();
        return back();
    }

    public function toggle_active($id){
        $department = Departments::find($id);

        $department->update([
            'is_active' => !$department->is_active
        ]);

        return redirect('/departments');
    }
    public function toggle_ban($user_id){
        $user = User::find($user_id);
        $user->update([
            'is_banned' => !$user->is_banned
        ]);

        return back();
    }

    public function dashboard(){
        $pending = Complaint::where('status','Pending')->count();
        $total = Complaint::count();
        $active = Complaint::where('status','Active')->count();
        $closed = Complaint::where('status','Closed')->count();
        $users = User::where('user_type','!=','Admin')->latest()->take(7)->get();
        $status = ['Active','Banned'];

        return view('admin.dashboard',[
            'pending' => $pending,
            'active' => $active,
            'total' => $total,
            'status' => $status,
            'closed' => $closed,
            'users' => $users
        ]);
    }

    public function students(){
        $students_a = User::where('is_banned',false)->where('user_type','Student')->count();
        $students_b = User::where('is_banned',true)->where('user_type','Student')->count();
        $students = User::latest()->where('user_type','Student')->paginate(16);

        return view('admin.student',[
            'banned' => $students_b,
            'active' => $students_a,
            'users' => $students,
        ]);
    }
    public function lecturers(){
        $students_a = User::where('is_banned',false)->where('user_type','Lecturer')->count();
        $students_b = User::where('is_banned',true)->where('user_type','Lecturer')->count();
        $students = User::latest()->where('user_type','Lecturer')->paginate(16);

        return view('admin.lecturer',[
            'banned' => $students_b,
            'active' => $students_a,
            'users' => $students,
        ]);
    }

    public function department($status){
        if($status == 'Active'){
            $department = Departments::where('is_active',true)->paginate(8);
        }else{
            $department = Departments::where('is_active',false)->paginate(8);
        }
        return view('admin.department',[
            'departments' => $department
        ]);
    }

    public function level(){
        $level = Level::latest()->paginate(8);
        $status = ['100','200','300','400','Final'];

        return view('admin.level',[
            'levels' => $level,
            'status' => $status,
        ]);
    }
    public function add_level(Request $request){
        $request->validate([
            'prefix' => 'required',
            'level' => 'required',
        ]);

        Level::create([
            'prefix' => $request->prefix,
            'level' => $request->level,
        ]);

        return back();
    }

    public function update_level(Request $request, $level_id){
        $request->validate([
            'level' => 'required',
        ]);
        $level= Level::find($level_id);

        $level->level = $request->level;
        $level->update();

        return back();
    }

    public function delete_level($level_id){
        
    }
}
