<?php

namespace App\Http\Controllers\stu;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ShortTermForeignStu;
use Illuminate\Support\Facades\Auth;

class ShortTermForeignStuController extends Controller
{
    //
	public function index(Request $request){
		$sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;

		$shortterm = ShortTermForeignStu::join('college_data',function($join){
    		$join->on('short_term_foreign_stu.college','college_data.college');
    		$join->on('short_term_foreign_stu.dept','college_data.dept');
    		})->paginate(20);
		$user = Auth::user();
		$data = compact('shortterm','user');
		return view('stu/short_term_foreign_stu',$data);
	}

	public function search (Request $request){

    	$sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;
        
        $shortterm = ShortTermForeignStu::join('college_data',function($join){
                $join->on('short_term_foreign_stu.college','college_data.college');
                $join->on('short_term_foreign_stu.dept','college_data.dept');
            });
        if($request->college != 0)
            $shortterm = $shortterm
                ->where('short_term_foreign_stu.college',$request->college);
        if($request->dept != 0)
            $shortterm = $shortterm
                ->where('short_term_foreign_stu.dept',$request->dept);
        if($request->name != "")
            $shortterm = $shortterm
                ->where('name',"like","%$request->name%");        
        if($request->stuLevel != "")
            $shortterm = $shortterm
                ->where('stuLevel', $request->stuLevel);                
        if($request->nation != "")
            $shortterm = $shortterm
                ->where('nation',"like","%$request->nation%");
        if($request->startDate != "")
            $shortterm = $shortterm
                ->where('startDate','>',"$request->startDate");
        if($request->endDate != "")
            $shortterm = $shortterm
                ->where('endDate','<',"$request->endDate");
        if($request->comments != "")
            $shortterm = $shortterm
                ->where('comments',"like","%$request->comments%");

        $shortterm = $shortterm->orderBy($sortBy,$orderBy)
            ->paginate(20);
        $shortterm->appends($request->except('page'));    
        $user = Auth::user();
        $data = compact('shortterm','user');
        return view('stu/short_term_foreign_stu',$data);
    }			
}
