<?php

namespace App\Http\Controllers\prof;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ProfAttendConference;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ProfAttendConferenceController extends Controller
{
    //
    public function index(Request $request){
        $sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;

    	$Pattendconference=ProfAttendConference::join('college_data',function($join){
            $join->on('prof_attend_conference.college','college_data.college');
            $join->on('prof_attend_conference.dept','college_data.dept');
        })->orderBy($sortBy,$orderBy)
            ->paginate(20);
        $user = Auth::user();
    	$data=compact('Pattendconference','user');
    	return view ('prof/prof_attend_conference',$data);
    }
    public function insert(Request $request){
    	
    	$this->validate($request,[
    		'college'=>'required|max:11',
    		'dept'=>'required|max:11',
    		'name'=>'required|max:20',
    		'profLevel'=>'required|max:11',
    		'nation'=>'required|max:20',
    		'confName'=>'required|max:200',
    		'startDate'=>'required',
    		'endDate'=>'required',
    		'comments'=>'max:500',
    		]);

    	profAttendConference::create($request->all());

        return redirect('prof_attend_conference')->with('success','新增成功');
    }

    public function search (Request $request){

        $sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;
        
        $Pattendconference = ProfAttendConference::join('college_data',function($join){
                $join->on('prof_attend_conference.college','college_data.college');
                $join->on('prof_attend_conference.dept','college_data.dept');
            });
        if($request->college != 0)
            $Pattendconference = $Pattendconference
                ->where('prof_attend_conference.college',$request->college);
        if($request->dept != 0)
            $Pattendconference = $Pattendconference
                ->where('prof_attend_conference.dept',$request->dept);
        if($request->name != "")
            $Pattendconference = $Pattendconference
                ->where('name',"like","%$request->name%");        
        if($request->profLevel != "")
            $Pattendconference = $Pattendconference
                ->where('profLevel', $request->profLevel);                
        if($request->nation != "")
            $Pattendconference = $Pattendconference
                ->where('nation',"like","%$request->nation%");
        if($request->confName != "")
            $Pattendconference = $Pattendconference
                ->where('confName',"like","%$request->confName%");
        if($request->comments != "")
            $Pattendconference = $Pattendconference
                ->where('comments',"like","%$request->comments%");
        if($request->startDate != "")
            $Pattendconference = $Pattendconference
                ->where('startDate','>',"$request->startDate");
        if($request->endDate != "")
            $Pattendconference = $Pattendconference
                ->where('endDate','<',"$request->endDate");

        $Pattendconference = $Pattendconference->orderBy($sortBy,$orderBy)
            ->paginate(20);
        $Pattendconference->appends($request->except('page'));    
        $user = Auth::user();
        $data = compact('Pattendconference','user');
        return view('prof/prof_attend_conference',$data);
    }

    public function edit($id){
        $Pattendconference = ProfAttendConference::find($id);
        if(Gate::allows('permission',$Pattendconference))
            return view('prof/prof_attend_conference_edit',$Pattendconference);
        return redirect('prof_attend_conference');
    }

    public function update($id,Request $request){
        $Pattendconference = ProfAttendConference::find($id);
        if(!Gate::allows('permission',$Pattendconference))
            return redirect('prof_attend_conference');
        $this->validate($request,[
            'college'=>'required|max:11',
            'dept'=>'required|max:11',
            'name'=>'required|max:20',
            'profLevel'=>'required|max:11',
            'nation'=>'required|max:20',
            'confName'=>'required|max:200',
            'startDate'=>'required',
            'endDate'=>'required',
            'comments'=>'max:500',
            ]);
        $Pattendconference->update($request->all());
        return redirect('prof_attend_conference')->with('success','更新成功');
    }
    public function delete($id){
        $Pattendconference = ProfAttendConference::find($id);
        if(!Gate::allows('permission',$Pattendconference))
            return redirect('prof_attend_conference');
        $Pattendconference->delete();
        return redirect('prof_attend_conference');
    }


}
