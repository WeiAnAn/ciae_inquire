<?php

namespace App\Http\Controllers\stu;

use Illuminate\Http\Request;
use App\StuFromPartnerSchool;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class StuFromPartnerSchoolController extends Controller
{
    //
    public function index(Request $request){
    	$sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;

		$frompartnerdata = StuFromPartnerSchool::join('college_data',function($join){
    		$join->on('stu_from_partner_school.college','college_data.college');
    		$join->on('stu_from_partner_school.dept','college_data.dept');
    		})->paginate(20);

		$user = Auth::user();
		$data = compact('frompartnerdata','user');
		return view('stu/stu_from_partner_school',$data);
	}

	public function search (Request $request){

    	$sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;
        
        $frompartnerdata = StuFromPartnerSchool::join('college_data',function($join){
                $join->on('stu_from_partner_school.college','college_data.college');
                $join->on('stu_from_partner_school.dept','college_data.dept');
            });
        if($request->college != 0)
            $frompartnerdata = $frompartnerdata
                ->where('stu_from_partner_school.college',$request->college);
        if($request->dept != 0)
            $frompartnerdata = $frompartnerdata
                ->where('stu_from_partner_school.dept',$request->dept);
        if($request->name != "")
            $frompartnerdata = $frompartnerdata
                ->where('name',"like","%$request->name%");        
        if($request->stuLevel != "")
            $frompartnerdata = $frompartnerdata
                ->where('stuLevel', $request->stuLevel);                
        if($request->nation != "")
            $frompartnerdata = $frompartnerdata
                ->where('nation',"like","%$request->nation%");
        if($request->startDate != "")
            $frompartnerdata = $frompartnerdata
                ->where('startDate','>',"$request->startDate");
        if($request->endDate != "")
            $frompartnerdata = $frompartnerdata
                ->where('endDate','<',"$request->endDate");
        if($request->comments != "")
            $frompartnerdata = $frompartnerdata
                ->where('comments',"like","%$request->comments%");

        $frompartnerdata = $frompartnerdata->orderBy($sortBy,$orderBy)
            ->paginate(20);
        $frompartnerdata->appends($request->except('page'));    
        $user = Auth::user();
        $data = compact('frompartnerdata','user');
        return view('stu/stu_from_partner_school',$data);
    }			
}
