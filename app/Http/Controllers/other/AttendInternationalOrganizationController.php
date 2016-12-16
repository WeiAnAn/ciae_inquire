<?php

namespace App\Http\Controllers\other;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AttendInternationalOrganization;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Excel;
use Validator;
use App\CollegeData;

class AttendInternationalOrganizationController extends Controller
{
    public function index(Request $request){
        $sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;

        $attendiorganization=AttendInternationalOrganization::
            join('college_data',function($join){
            $join->on('attend_international_organization.college','college_data.college');
            $join->on('attend_international_organization.dept','college_data.dept');
        })->orderBy($sortBy,$orderBy)
            ->paginate(20);
        $user = Auth::user();
        $data=compact('attendiorganization','user');


    	return view ('other/attend_international_organization',$data);
    }
    public function insert(Request $request){
      
        $this->validate($request,[
        'college'=>'required|max:11',
        'dept'=>'required|max:11',
        'name'=>'required|max:20',
        'organization'=>'required|max:200',
        'startDate'=>'required',
        'endDate'=>'required',
        'comments'=>'max:500',
        ]);
        AttendInternationalOrganization::create($request->all());

        return redirect('attend_international_organization')->with('success','新增成功');
    }
    
    public function search (Request $request){
        $sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;

        $attendiorganization = attendinternationalorganization::join('college_data',function($join){
                $join->on('attend_international_organization.college','college_data.college');
                $join->on('attend_international_organization.dept','college_data.dept');
            });
        if($request->college != 0)
            $attendiorganization = $attendiorganization
                ->where('attend_international_organization.college',$request->college);
        if($request->dept != 0)
            $attendiorganization = $attendiorganization
                ->where('attend_international_organization.dept',$request->dept);
        if($request->name != "")
            $attendiorganization = $attendiorganization
                ->where('name',"like","%$request->name%"); 
        if($request->organization != "")
            $attendiorganization = $attendiorganization
                ->where('organization',"like","%$request->organization%"); 
        if($request->startDate != "")
            $attendiorganization = $attendiorganization
                ->where('startDate','>=',"$request->startDate");
        if($request->endDate != "")
            $attendiorganization = $attendiorganization
                ->where('endDate','<=',"$request->endDate");
        if($request->comments != "")
            $attendiorganization = $attendiorganization
                ->where('comments',"like","%$request->comments%");

        $attendiorganization = $attendiorganization->orderBy($sortBy,$orderBy)
            ->paginate(20);
        $attendiorganization->appends($request->except('page'));    
        $user = Auth::user();
        $data = compact('attendiorganization','user');
        return view('other/attend_international_organization',$data);
    }


    public function delete($id){
        $AIO = AttendInternationalOrganization::find($id);
        if(!Gate::allows('permission',$AIO))
            return redirect('attend_international_organization');
        $AIO->delete();
        return redirect('attend_international_organization');
    }


}
