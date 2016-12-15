<?php

namespace App\Http\Controllers\other;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AttendInternationalOrganization;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Excel;
use Validator;


class AttendInternationalOrganizationController extends Controller
{
    public function index(Request $request){
        $sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;

        $attendiorganization=attendinternationalorganization::
            join('college_data',function($join){
            $join->on('attend_international_organization.college','college_data.college');
            $join->on('attend_international_organization.dept','college_data.dept');
        })->orderBy($sortBy,$orderBy)
            ->paginate(20);
        $user = Auth::user();
        $data=compact('attendiorganization','user');


    	return view ('other/attend_international_organization',$data);
    }
}
