<?php

namespace App\Http\Controllers\stu;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\StuForeignResearch;
use Illuminate\Support\Facades\Auth;


class StuForeignResearchController extends Controller
{
    //
     public function index (Request $request){
     	$sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;

    	$foreignreseach = StuForeignResearch::join('college_data',function($join){
    		$join->on('stu_foreign_research.college','college_data.college');
    		$join->on('stu_foreign_research.dept','college_data.dept');
    		})->paginate(20);

    	$user = Auth::user();
		$data = compact('foreignreseach','user');
		return view('stu/stu_foreign_research',$data);
    	}
    public function search (Request $request){

    	$sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;
        
        $foreignreseach = StuForeignResearch::join('college_data',function($join){
                $join->on('stu_foreign_research.college','college_data.college');
                $join->on('stu_foreign_research.dept','college_data.dept');
            });
        if($request->college != 0)
            $foreignreseach = $foreignreseach
                ->where('stu_foreign_research.college',$request->college);
        if($request->dept != 0)
            $foreignreseach = $foreignreseach
                ->where('stu_foreign_research.dept',$request->dept);
        if($request->name != "")
            $foreignreseach = $foreignreseach
                ->where('name',"like","%$request->name%");        
        if($request->stuLevel != "")
            $foreignreseach = $foreignreseach
                ->where('stuLevel', $request->stuLevel);                
        if($request->nation != "")
            $foreignreseach = $foreignreseach
                ->where('nation',"like","%$request->nation%");  

        if($request->comments != "")
            $foreignreseach = $foreignreseach
                ->where('comments',"like","%$request->comments%");

        $foreignreseach = $foreignreseach->orderBy($sortBy,$orderBy)
            ->paginate(20);
        $foreignreseach->appends($request->except('page'));    
        $user = Auth::user();
        $data = compact('foreignreseach','user');
        return view('stu/stu_foreign_research',$data);
    }			
}
