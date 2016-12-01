<?php

namespace App\Http\Controllers\prof;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ForeignProfVist;
use Illuminate\Support\Facades\Auth;

class ForeignProfVistController extends Controller
{
    //
    public function index(Request $request){
    	$sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;

    	$foreignPvist=ForeignProfVist::join('college_data',function($join){
            $join->on('foreign_prof_vist.college','college_data.college');
            $join->on('foreign_prof_vist.dept','college_data.dept');
        })->orderBy($sortBy,$orderBy)
            ->paginate(20);
        $user = Auth::user();
    	$data=compact('foreignPvist','user');
    	return view ('prof/foreign_prof_vist',$data);
    }

    public function search (Request $request){

    	$sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;
        
        $foreignPvist = ForeignProfVist::join('college_data',function($join){
                $join->on('foreign_prof_vist.college','college_data.college');
                $join->on('foreign_prof_vist.dept','college_data.dept');
            });
        if($request->college != 0)
            $foreignPvist = $foreignPvist
                ->where('foreign_prof_vist.college',$request->college);
        if($request->dept != 0)
            $foreignPvist = $foreignPvist
                ->where('foreign_prof_vist.dept',$request->dept);
        if($request->name != "")
            $foreignPvist = $foreignPvist
                ->where('name',"like","%$request->name%");        
        if($request->profLevel != "")
            $foreignPvist = $foreignPvist
                ->where('profLevel', $request->profLevel);                
        if($request->nation != "")
            $foreignPvist = $foreignPvist
                ->where('nation',"like","%$request->nation%");
        if($request->startDate != "")
            $foreignPvist = $foreignPvist
                ->where('startDate','>',"$request->startDate");
        if($request->endDate != "")
            $foreignPvist = $foreignPvist
                ->where('endDate','<',"$request->endDate");
        if($request->comments != "")
            $foreignPvist = $foreignPvist
                ->where('comments',"like","%$request->comments%");

        $foreignPvist = $foreignPvist->orderBy($sortBy,$orderBy)
            ->paginate(20);
        $foreignPvist->appends($request->except('page'));    
        $user = Auth::user();
        $data = compact('foreignPvist','user');
        return view('prof/foreign_prof_vist',$data);
    }
}
