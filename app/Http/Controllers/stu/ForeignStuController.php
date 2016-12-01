<?php

namespace App\Http\Controllers\stu;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ForeignStu;
use Illuminate\Support\Facades\Auth;

class ForeignStuController extends Controller
{
    //
    public function index(Request $request){
    	$sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;

    	$foreignStu = ForeignStu::join('college_data',function($join){
    		$join->on('foreign_stu.college','college_data.college');
    		$join->on('foreign_stu.dept','college_data.dept');
    		})->paginate(20);
    	$user=Auth::user();
    	$data = compact('foreignStu','user');
    	return view ('stu/foreign_stu',$data);

    }

    public function search (Request $request){

    	$sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;
        
        $foreignStu = ForeignStu::join('college_data',function($join){
                $join->on('foreign_stu.college','college_data.college');
                $join->on('foreign_stu.dept','college_data.dept');
            });
        if($request->college != 0)
            $foreignStu = $foreignStu
                ->where('foreign_stu.college',$request->college);
        if($request->dept != 0)
            $foreignStu = $foreignStu
                ->where('foreign_stu.dept',$request->dept);
        if($request->stuID != "")
            $foreignStu = $foreignStu
                ->where('stuID',"like","%$request->stuID%"); 
        if($request->chtName != "")
            $foreignStu = $foreignStu
                ->where('chtName',"like","%$request->chtName%"); 
        if($request->engName != "")
            $foreignStu = $foreignStu
                ->where('engName',"like","%$request->engName%");       
        if($request->stuLevel != "")
            $foreignStu = $foreignStu
                ->where('stuLevel', $request->stuLevel);                
        if($request->nation != "")
            $foreignStu = $foreignStu
                ->where('nation',"like","%$request->nation%");
        if($request->startDate != "")
            $foreignStu = $foreignStu
                ->where('startDate','>',"$request->startDate");
        if($request->endDate != "")
            $foreignStu = $foreignStu
                ->where('endDate','<',"$request->endDate");
        if($request->comments != "")
            $foreignStu = $foreignStu
                ->where('comments',"like","%$request->comments%");

        $foreignStu = $foreignStu->orderBy($sortBy,$orderBy)
            ->paginate(20);
        $foreignStu->appends($request->except('page'));    
        $user = Auth::user();
        $data = compact('foreignStu','user');
        return view('stu/foreign_stu',$data);
    }			
}
