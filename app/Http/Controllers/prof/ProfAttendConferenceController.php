<?php

namespace App\Http\Controllers\prof;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ProfAttendConference;
use Illuminate\Support\Facades\Auth;

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
    	//validate 暫無失敗訊息
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

    	/*
    	profAttendConference::create([
    		'college'=>$request['college'],
    		'dept'=>$request['dept'],
    		'name'=>$request['name'],
    		'profLevel'=>$request['profLevel'],
    		'nation'=>$request['nation'],
    		'confName'=>$request['confName'],
    		'startDate'=>$request['startDate'],
    		'endDate'=>$request['endDate'],
    		'comments'=>$request['comments']
    	]);   	 	
    	
     	$prof_attend_conference = new ProfAttendConference;
    	$prof_attend_conference->college=$request['college'];
    	$prof_attend_conference->dept=$request['dept'];
    	$prof_attend_conference->name=$request['name'];
    	$prof_attend_conference->profLevel=$request['profLevel'];
    	$prof_attend_conference->nation=$request['nation'];
		$prof_attend_conference->confName=$request['confName'];
		$prof_attend_conference->startDate=$request['startDate'];
		$prof_attend_conference->endDate=$request['endDate'];
		$prof_attend_conference->comments=$request['comments'];
		$prof_attend_conference->save();		
		*/
		return redirect('prof_attend_conference')->with('success','新增成功'); //view中未新增顯示成功功能
		
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

        $Pattendconference = $Pattendconference->orderBy($sortBy,$orderBy)
            ->paginate(20);
        $Pattendconference->appends($request->except('page'));    
        $user = Auth::user();
        $data = compact('Pattendconference','user');
        return view('prof/prof_attend_conference',$data);
    }
}
