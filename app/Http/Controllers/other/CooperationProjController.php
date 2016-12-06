<?php

namespace App\Http\Controllers\other;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CooperationProj;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CooperationProjController extends Controller
{
    //
    public function index(Request $request){
    	$sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;

    	$cooperationproj= CooperationProj::join('college_data',function($join){
    		$join->on('cooperation_proj.college','college_data.college');
    		$join->on('cooperation_proj.dept','college_data.dept');
    		})->paginate(20);
        $cooperationproj->appends($request->except('page')); 
    	$user=Auth::user();
    	$data=compact('cooperationproj','user');
    	return view ('other/cooperation_proj',$data);
    }

    public function insert(Request $request){
        
        $this->validate($request,[
            'college'=>'required|max:11',
            'dept'=>'required|max:11',
            'name'=>'required|max:10',
            'projName'=>'required|max:200',
            'projOrg'=>'required|max:200',
            'projContent'=>'max:200',
            'startDate'=>'required',
            'endDate'=>'required',
            'comments'=>'max:500',
            ]);

        cooperationproj::create($request->all());

        return redirect('cooperation_proj')->with('success','新增成功');
    }

    public function search (Request $request){
    	$sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;

        $cooperationproj = CooperationProj::join('college_data',function($join){
                $join->on('cooperation_proj.college','college_data.college');
                $join->on('cooperation_proj.dept','college_data.dept');
            });
        if($request->college != 0)
            $cooperationproj = $cooperationproj
                ->where('cooperation_proj.college',$request->college);
        if($request->dept != 0)
            $cooperationproj = $cooperationproj
                ->where('cooperation_proj.dept',$request->dept);
        if($request->projOrg != "")
            $cooperationproj = $cooperationproj
                ->where('projOrg',"like","%$request->projOrg%"); 
        if($request->name != "")
            $cooperationproj = $cooperationproj
                ->where('name',"like","%$request->name%"); 
        if($request->projName != "")
            $cooperationproj = $cooperationproj
                ->where('projName',"like","%$request->projName%"); 
        if($request->startDate != "")
            $cooperationproj = $cooperationproj
                ->where('startDate','>',"$request->startDate");
        if($request->endDate != "")
            $cooperationproj = $cooperationproj
                ->where('endDate','<',"$request->endDate");
        if($request->comments != "")
            $cooperationproj = $cooperationproj
                ->where('comments',"like","%$request->comments%");

        $cooperationproj = $cooperationproj->orderBy($sortBy,$orderBy)
            ->paginate(20);
        $cooperationproj->appends($request->except('page'));    
        $user = Auth::user();
        $data = compact('cooperationproj','user');
        return view('other/cooperation_proj',$data);




    }
       public function delete($id){
        $cooperationproj = CooperationProj::find($id);
        if(!Gate::allows('permission',$cooperationproj))
            return redirect('cooperation_proj');
        $cooperationproj->delete();
        return redirect('cooperation_proj');
        }   
}
