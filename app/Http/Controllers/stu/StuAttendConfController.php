<?php

namespace App\Http\Controllers\stu;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\StuAttendConf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class StuAttendConfController extends Controller
{
    //
    public function index (Request $request){
    	$sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;

    	$conf = StuAttendConf::join('college_data',function($join){
    		$join->on('stu_attend_conf.college','college_data.college');
    		$join->on('stu_attend_conf.dept','college_data.dept');
    		})->paginate(20);
    	$user = Auth::user();
    	$data=compact('conf','user');
    	return view ('stu/stu_attend_conf',$data);
    	}

    public function insert(Request $request){
            
            $this->validate($request,[
                'college'=>'required|max:11',
                'dept'=>'required|max:11',
                'name'=>'required|max:20',
                'stuLevel'=>'required|max:11',
                'nation'=>'required|max:20',
                'confName'=>'required|max:200',
                'startDate'=>'required',
                'endDate'=>'required',
                'comments'=>'max:500',
                ]);

            StuAttendConf::create($request->all());

            return redirect('stu_attend_conf')->with('success','新增成功');
        }

    public function search (Request $request){

    	$sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;
        
        $conf = StuAttendConf::join('college_data',function($join){
                $join->on('stu_attend_conf.college','college_data.college');
                $join->on('stu_attend_conf.dept','college_data.dept');
            });
        if($request->college != 0)
            $conf = $conf
                ->where('stu_attend_conf.college',$request->college);
        if($request->dept != 0)
            $conf = $conf
                ->where('stu_attend_conf.dept',$request->dept);
        if($request->name != "")
            $conf = $conf
                ->where('name',"like","%$request->name%");        
        if($request->stuLevel != "")
            $conf = $conf
                ->where('stuLevel', $request->stuLevel);                
        if($request->nation != "")
            $conf = $conf
                ->where('nation',"like","%$request->nation%");
        if($request->confName != "")
            $conf = $conf
                ->where('confName',"like","%$request->confName%"); 
        if($request->startDate != "")
            $conf = $conf
                ->where('startDate','>',"$request->startDate");
        if($request->endDate != "")
            $conf = $conf
                ->where('endDate','<',"$request->endDate"); 
        if($request->comments != "")
            $conf = $conf
                ->where('comments',"like","%$request->comments%");

        $conf = $conf->orderBy($sortBy,$orderBy)
            ->paginate(20);
        $conf->appends($request->except('page'));    
        $user = Auth::user();
        $data = compact('conf','user');
        return view('stu/stu_attend_conf',$data);
    }	
     public function delete($id){
        $conf = StuAttendConf::find($id);
        if(!Gate::allows('permission',$conf))
            return redirect('stu_attend_conf');
        $conf->delete();
        return redirect('stu_attend_conf');
    }
}
