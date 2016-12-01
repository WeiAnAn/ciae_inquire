<?php

namespace App\Http\Controllers\stu;

use Illuminate\Http\Request;
use App\StuToPartnerSchool;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class StuToPartnerSchoolController extends Controller
{
    //
    public function index (Request $request){
    	$sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;

    	$topartnerdata = StuToPartnerSchool::join('college_data',function($join){
    		$join->on('stu_to_partner_school.college','college_data.college');
    		$join->on('stu_to_partner_school.dept','college_data.dept');
    		})->paginate(20);
    	$user = Auth::user();
		$data = compact('topartnerdata','user');
		return view('stu/stu_to_partner_school',$data);
    	}
    	
    public function search (Request $request){

    	$sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;
        
        $topartnerdata = StuToPartnerSchool::join('college_data',function($join){
                $join->on('stu_to_partner_school.college','college_data.college');
                $join->on('stu_to_partner_school.dept','college_data.dept');
            });
        if($request->college != 0)
            $topartnerdata = $topartnerdata
                ->where('stu_to_partner_school.college',$request->college);
        if($request->dept != 0)
            $topartnerdata = $topartnerdata
                ->where('stu_to_partner_school.dept',$request->dept);
        if($request->name != "")
            $topartnerdata = $topartnerdata
                ->where('name',"like","%$request->name%");        
        if($request->stuLevel != "")
            $topartnerdata = $topartnerdata
                ->where('stuLevel', $request->stuLevel);                
        if($request->nation != "")
            $topartnerdata = $topartnerdata
                ->where('nation',"like","%$request->nation%");
        if($request->startDate != "")
            $topartnerdata = $topartnerdata
                ->where('startDate','>',"$request->startDate");
        if($request->endDate != "")
            $topartnerdata = $topartnerdata
                ->where('endDate','<',"$request->endDate");
        if($request->comments != "")
            $topartnerdata = $topartnerdata
                ->where('comments',"like","%$request->comments%");

        $topartnerdata = $topartnerdata->orderBy($sortBy,$orderBy)
            ->paginate(20);
        $topartnerdata->appends($request->except('page'));    
        $user = Auth::user();
        $data = compact('topartnerdata','user');
        return view('stu/stu_to_partner_school',$data);
    }		
}
