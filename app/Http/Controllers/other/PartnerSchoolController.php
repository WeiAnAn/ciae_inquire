<?php

namespace App\Http\Controllers\other;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PartnerSchool;
use Illuminate\Support\Facades\Auth;

class PartnerSchoolController extends Controller
{
    //
    public function index (Request $request){
    	$sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;

    	$partner= PartnerSchool::join('college_data',function($join){
    		$join->on('partner_school.college','college_data.college');
    		$join->on('partner_school.dept','college_data.dept');
    		})->paginate(20);
    	$user=Auth::user();
    	$data=compact('partner','user');

    	return view ('other/partner_school',$data);
    }

    public function search (Request $request){
    	$sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;

        $partner = PartnerSchool::join('college_data',function($join){
                $join->on('partner_school.college','college_data.college');
                $join->on('partner_school.dept','college_data.dept');
            });
        if($request->college != 0)
            $partner = $partner
                ->where('partner_school.college',$request->college);
        if($request->dept != 0)
            $partner = $partner
                ->where('partner_school.dept',$request->dept);        
        if($request->nation != "")
            $partner = $partner
                ->where('nation',"like","%$request->nation%"); 
        if($request->chtName != "")
            $partner = $partner
                ->where('chtName',"like","%$request->chtName%"); 
        if($request->engName != "")
            $partner = $partner
                ->where('engName',"like","%$request->engName%");
        if($request->startDate != "")
            $internationalactivity = $internationalactivity
                ->where('startDate','>',"$request->startDate");
        if($request->endDate != "")
            $internationalactivity = $internationalactivity
                ->where('endDate','<',"$request->endDate");
        if($request->comments != "")
            $partner = $partner
                ->where('comments','like',"%$request->comments%");


        $partner = $partner->orderBy($sortBy,$orderBy)
            ->paginate(20);
        $partner->appends($request->except('page'));    
        $user = Auth::user();
        $data = compact('partner','user');
        return view('other/partner_school',$data);
    }
}
