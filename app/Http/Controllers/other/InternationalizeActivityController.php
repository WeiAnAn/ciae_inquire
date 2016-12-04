<?php

namespace App\Http\Controllers\other;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\InternationalizeActivity;
use Illuminate\Support\Facades\Auth;

class InternationalizeActivityController extends Controller
{
    //
    public function index(Request $request){
    	$sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;

    	$internationalactivity= InternationalizeActivity::join('college_data',function($join){
    		$join->on('internationalize_activity.college','college_data.college');
    		$join->on('internationalize_activity.dept','college_data.dept');
    		})->paginate(20);
    	$user= Auth::user();
    	$data=compact('internationalactivity','user');

    	return view ('other/internationalize_activity',$data);
    }
    public function search (Request $request){
    	$sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;

        $internationalactivity = InternationalizeActivity::join('college_data',function($join){
                $join->on('internationalize_activity.college','college_data.college');
                $join->on('internationalize_activity.dept','college_data.dept');
            });
        if($request->college != 0)
            $internationalactivity = $internationalactivity
                ->where('internationalize_activity.college',$request->college);
        if($request->dept != 0)
            $internationalactivity = $internationalactivity
                ->where('internationalize_activity.dept',$request->dept);        
        if($request->activityName != "")
            $internationalactivity = $internationalactivity
                ->where('activityName',"like","%$request->activityName%"); 
        if($request->place != "")
            $internationalactivity = $internationalactivity
                ->where('place',"like","%$request->place%"); 
        if($request->host != "")
            $internationalactivity = $internationalactivity
                ->where('host',"like","%$request->host%");
        if($request->guest != "")
            $internationalactivity = $internationalactivity
                ->where('guest',"like","%$request->guest%"); 
        if($request->startDate != "")
            $internationalactivity = $internationalactivity
                ->where('startDate','>',"$request->startDate");
        if($request->endDate != "")
            $internationalactivity = $internationalactivity
                ->where('endDate','<',"$request->endDate");


        $internationalactivity = $internationalactivity->orderBy($sortBy,$orderBy)
            ->paginate(20);
        $internationalactivity->appends($request->except('page'));    
        $user = Auth::user();
        $data = compact('internationalactivity','user');
        return view('other/internationalize_activity',$data);
    }
}
