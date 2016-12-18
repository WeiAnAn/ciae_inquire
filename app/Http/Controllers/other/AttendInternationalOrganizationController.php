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

    public function edit($id){
        $attendiorganization = attendinternationalorganization::find($id);
        if(Gate::allows('permission',$attendiorganization))
            return view('other/attend_international_organization_edit',$attendiorganization);
        return redirect('attend_international_organization');
    }

    public function update($id,Request $request){
        $attendiorganization = attendinternationalorganization::find($id);
        if(!Gate::allows('permission',$attendiorganization))
            return redirect('attend_international_organization');
        $this->validate($request,[
            'college'=>'required|max:11',
            'dept'=>'required|max:11',
            'name'=>'required|max:20',
            'organization'=>'required|max:200',
            'startDate'=>'required',
            'endDate'=>'required',
            'comments'=>'max:500',
        ]);
        $attendiorganization->update($request->all());
        return redirect('attend_international_organization')->with('success','更新成功');
    }


    public function delete($id){
        $AIO = AttendInternationalOrganization::find($id);
        if(!Gate::allows('permission',$AIO))
            return redirect('attend_international_organization');
        $AIO->delete();
        return redirect('attend_international_organization');
    }


    public function upload(Request $request){
        Excel::load($request->file('file'),function($reader){
            $array = $reader->toArray();
            $newArray = [];
            foreach ($array as $item) {
                foreach ($item as $key => $value) {

                    switch ($key) {
                        case '所屬一級單位':
                            $item['college'] = $value;
                            unset($item[$key]);
                            break;
                        case '所屬系所部門':
                            $item['dept'] = $value;
                            unset($item[$key]);
                            break;
                        case '參加人':
                            $item['name'] = $value;
                            unset($item[$key]);
                            break;
                        case '組織名稱':
                            $item['organization'] = $value;
                            unset($item[$key]);
                            break;
                        case '開始時間':
                            $item['startDate'] = $value;
                            unset($item[$key]);
                            break;
                        case '結束時間':
                            $item['endDate'] = $value;
                            unset($item[$key]);                            
                        case '備註':
                            $item['comments'] = $value;
                            unset($item[$key]);
                            break;          
                            break;                        
                        default:
                            $validator = Validator::make($item,[]);
                            $validator->errors()->add('format','檔案欄位錯誤');
                            return redirect('attend_international_organization')
                                ->withErrors($validator,"upload");
                            break;
                    }
                }
                $validator = Validator::make($item,[
                    'college'=>'required|max:11',
                    'dept'=>'required|max:11',
                    'name'=>'required|max:20',
                    'organization'=>'required|max:200',
                    'startDate'=>'required',
                    'endDate'=>'required',
                    'comments'=>'max:500',
                ]);
                if($validator->fails()){
                    return redirect('attend_international_organization')
                        ->withErrors($validator,"upload");
                }
                if(CollegeData::where('college',$item['college'])
                        ->where('dept',$item['dept'])->first()==null){
                    $validator->errors()->add('number','系所代碼錯誤');
                    return redirect('attend_international_organization')
                                ->withErrors($validator,"upload");
                }
                if(!Gate::allows('permission',(object)$item)){
                    $validator->errors()->add('permission','無法新增未有權限之系所部門');
                    return redirect('attend_international_organization')
                                ->withErrors($validator,"upload");
                }
                array_push($newArray,$item);
            }
            attendinternationalorganization::insert($newArray);
        });
        return redirect('attend_international_organization');
    }

    public function example(Request $request){
        return response()->download(public_path().'/Excel_example/other/attend_international_organization.xlsx',"參與國際組織.xlsx");
    }  
}
