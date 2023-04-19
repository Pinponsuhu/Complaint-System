<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\ComplaintAttachments;
use App\Models\Lecturer;
use App\Models\Level;
use App\Models\Message;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function dashboard(){

        $pending = Complaint::where('user_id',auth()->user()->id)->where('status','Pending')->count();
        $total = Complaint::where('user_id',auth()->user()->id)->count();
        $active = Complaint::where('user_id',auth()->user()->id)->where('status','Active')->count();
        $closed = Complaint::where('user_id',auth()->user()->id)->where('status','Closed')->count();
        $complaints = Complaint::where('user_id',auth()->user()->id)->take(7)->get();
        $status = ['Active','Closed','Pending'];
    return view('dashboard',[
        'pending' => $pending,
        'active' => $active,
        'total' => $total,
        'status' => $status,
        'closed' => $closed,
        'complaints' => $complaints
    ]);
    }
    public function lecturer_dashboard(){
        $me = Lecturer::where('user_id',auth()->user()->id)->first();
        $pending = Complaint::where('level', $me->level_assigned)->where('status','Pending')->count();
        $total = Complaint::where('level', $me->level_assigned)->count();
        $active = Complaint::where('level', $me->level_assigned)->where('status','Active')->count();
        $closed = Complaint::where('level', $me->level_assigned)->where('status','Closed')->count();
        $users = User::where('user_type','Lecturer')->take(6)->get();
        $complaint = Complaint::where('level',$me->level_assigned)->take(6)->get();
        $status =[true,false];
        $status2 =["Active","Pending","Closed"];
    return view('lecturer-dashboard',[
        'pending' => $pending,
        'users' => $users,
        'active' => $active,
        'total' => $total,
        'status' => $status,
        'status2' => $status2,
        'closed' => $closed,
        'complaints' => $complaint,
    ]);
    }

    public function index($status){
        // dd(auth()->user()->id);
        if(auth()->user()->user_type == 'Student'){
        $student = Student::where('user_id',auth()->user()->id)->first();
        $pending = Complaint::where('status',"Pending")->where('matric_number',$student->matric_number)->count();
        $active = Complaint::where('status',"Active")->where('matric_number',$student->matric_number)->count();
        $closed = Complaint::where('status',"Closed")->where('matric_number',$student->matric_number)->count();
        $total = Complaint::where('matric_number',$student->matric_number)->count();

       if ($status == "Active") {
        $complaints = Complaint::where('matric_number',$student->matric_number)->where('status',"Active")->latest()->paginate(12);
       }else if($status == "Closed"){
            $complaints = Complaint::where('matric_number',$student->matric_number)->where('status',"Closed")->latest()->paginate(12);
       }else if($status == "Pending"){
            $complaints = Complaint::where('matric_number',$student->matric_number)->where('status',"Pending")->latest()->paginate(12);
       }else if($status == "All"){
        $complaints = Complaint::where('matric_number',$student->matric_number)->latest()->paginate(12);
       }else{
        return abort(403,"Invalid URL Endpoint");
       }

       return view('complaints',[
        'pending' => $pending,
        'active' => $active,
        'total' => $total,
        'closed' => $closed,
        'complaints' => $complaints,
       ]);
        }else{
        $statuses = ['Active','Closed','Pending'];
        $lecturer = Lecturer::where('user_id',auth()->user()->id)->first();
        // dd($lecturer);
        $pending = Complaint::where('status',"Pending")->where('department_id',$lecturer->department_id)->count();
        $active = Complaint::where('status',"Active")->where('department_id',$lecturer->department_id)->count();
        $closed = Complaint::where('status',"Closed")->where('department_id',$lecturer->department_id)->count();
        $total = Complaint::where('department_id',$lecturer->department_id)->count();

       if ($status == "Active") {
        $complaints = Complaint::where('department_id',$lecturer->department_id)->where('status',"Active")->latest()->paginate(12);
       }else if($status == "Closed"){
            $complaints = Complaint::where('department_id',$lecturer->department_id)->where('status',"Closed")->latest()->paginate(12);
       }else if($status == "Pending"){
            $complaints = Complaint::where('department_id',$lecturer->department_id)->where('status',"Pending")->latest()->paginate(12);
       }else if($status == "All"){
        $complaints = Complaint::where('department_id',$lecturer->department_id)->latest()->paginate(12);
       }else{
        return abort(403,"Invalid URL Endpoint");
       }

       return view('complaints',[
        'pending' => $pending,
        'active' => $active,
        'status' => $statuses,
        'total' => $total,
        'closed' => $closed,
        'complaints' => $complaints,
       ]);
        }
    }

    public function add_new(Request $request){
        $request->validate([
            'title' => 'required|max:120',
            'description' => 'required|min:40|max:400',
            'attachments*' => 'mimes:png,jpg,jpeg,doc,docx'
        ]);

        // dd($request->all());
        $complaint_count = Complaint::count();
        $prev = Complaint::latest()->first();
        if($complaint_count == 0){
            $c_id = 0;
        }else{
            $c_id = $prev->complaint_id + 1;
        }
        // dd($prefix);
        $student_det = Student::where('user_id',auth()->user()->id)->first();
        $prefix = $student_det->matric_number[0] . $student_det->matric_number[1];
        $level = Level::where('prefix', $prefix)->first();
        $complaint = Complaint::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => "Active",
            "matric_number" => $student_det->matric_number,
            'complaint_id' => $c_id,
            'department_id' => $student_det->matric_number[2] . $student_det->matric_number[3] . $student_det->matric_number[4] .$student_det->matric_number[5],
            'user_id' => auth()->user()->id,
            'level' => $level->level
        ]);


        $dest = '/public/complaint_file';
        $files = $request->file('attachments');
        if($request->hasFile('attachments')){
            foreach ($files as $attachment) {
                $path = $attachment->store($dest);
                ComplaintAttachments::create([
                    'attachment' => str_replace('public/complaint_file/','',$path),
                    'complaint_id' => $complaint->id
                ]);
            }
        }
        return redirect('/complaint/All');
    }

    public function all_message($complaint_id){
        if(auth()->user()->user_type == 'Student'){
            $complaint = Complaint::where('id',$complaint_id)->where('user_id',auth()->user()->id)->first();
        $count = Complaint::where('id',$complaint_id)->where('user_id',auth()->user()->id)->count();

        if($count > 0){
            $attachments = ComplaintAttachments::where('complaint_id',$complaint_id)->get();
        $messages = Message::where('complaint_id',$complaint->id)->get();
        return view('message', [
            'messages' => $messages,
            'attachments' => $attachments,
            'complaint' => $complaint,
        ]);
        }else{
                return abort(404,'Page not found');
        }
    }else if(auth()->user()->user_type == 'Lecturer'){
            $complain = Complaint::find($complaint_id);
            $lecturer = Lecturer::where('user_id',auth()->user()->id)->first();
            $student = Student::where('user_id',$complain->user_id)->first();
            if($lecturer->level_assigned == $student->level){
            $complaint = Complaint::where('id',$complaint_id)->where('department_id',$lecturer->department_id)->first();
            $attachments = ComplaintAttachments::where('complaint_id',$complaint_id)->get();
        $messages = Message::where('complaint_id',$complaint->id)->get();
                return view('message', [
                    'messages' => $messages,
                    'attachments' => $attachments,
                    'complaint' => $complaint,
                ]);
            }else{
                return abort(403,"Unauthorized access");
            }
    }




    }



    public function send_message(Request $request, $complaint_id){
        $request->validate([
            'message' => 'required'
        ]);
        if(auth()->user()->user_type == 'Student'){
            $complaint = Complaint::find($complaint_id);
        if($complaint->user_id != auth()->user()->id){
            return abort(403, "Unauthorized access");
        }
        $student = Student::where('user_id',auth()->user()->id)->first();
        $lecturer= Lecturer::where('level_assigned', $student->level)->first();

        Message::create(
            [
                'from' => $complaint->user_id,
                'to' => $lecturer->user_id,
                'complaint_id' => $complaint->id,
                'message' => $request->message
            ]
        );
        }else if(auth()->user()->user_type == 'Lecturer'){
            $complaint = Complaint::find($complaint_id);
            $lecturer = Lecturer::where('department_id',$complaint->department_id)->where('level_assigned',$complaint->level)->first();
            if($lecturer->user_id != auth()->user()->id){
                return abort(403, "Unauthorized access");
            }
            Message::create(
                [
                    'from' => auth()->user()->id,
                    'to' => $complaint->user_id,
                    'complaint_id' => $complaint->id,
                    'message' => $request->message
                ]
            );
        }

        return redirect('/complaint-trail/' . $complaint->id);

    }

    public function change_status(Request $request, $complaint_id){
        if(auth()->user()->user_type == 'Lecturer'){
            $complaint = Complaint::find($complaint_id);
        $complaint->update([
            'status' => $request->status
        ]);
        return back();
        }else{
        return abort(403);
    }
}

}
