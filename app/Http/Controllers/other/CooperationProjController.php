<?php

namespace App\Http\Controllers\other;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CooperationProj;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Excel;
use Validator;
use App\CollegeData;

class CooperationProjController extends Controller
{
    //
    public function index(Request $request){
    	$sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;
    	$cooperationproj= CooperationProj::join('college_data',function($join){
    		$join->on('cooperation_proj.college','college_data.college');
    		$join->on('cooperation_proj.dept','college_data.dept');
    		})->orderBy($sortBy,$orderBy)->paginate(20);
        $cooperationproj->appends($request->except('page')); 
    	$user=Auth::user();
    	$data=compact('cooperationproj','user');
    	return view ('other/cooperation_proj',$data);
    }

    public function insert(Request $request){

        $rules=[
            'college'=>'required|max:11',
            'dept'=>'required|max:11',
            'name'=>'required|max:10',
            'projName'=>'required|max:200',
            'projOrg'=>'required|max:200',
            'startDate'=>'required',
            'endDate'=>'required',
            'comments'=>'max:500',
        ];

        $validator=Validator::make($request->all(),$rules);

        if($request->startDate > $request->endDate){
            $validator->errors()->add('endDate','開始時間必須在結束時間前');
            return redirect('cooperation_proj')->withErrors($validator)->withInput();
        }

        if($validator->fails()){
            return redirect('cooperation_proj')->withErrors($validator)->withInput();
        }

        cooperationproj::create($request->all());
        return redirect('cooperation_proj')->with('success','新增成功');

    }

    public function search (Request $request){
    	$sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;

        $cooperationproj = CooperationProj::join('college_data',function($join){
                $join->on('cooperation_proj.college','college_data.college');
                $join->on('cooperation_proj.dept','college_data.dept');
            });
        if($request->college != 0)
            $cooperationproj = $cooperationproj
                ->where('cooperation_proj.college',$request->college);
        if($request->dept != 0)
            $cooperationproj = $cooperationproj
                ->where('cooperation_proj.dept',$request->dept);
        if($request->projOrg != "")
            $cooperationproj = $cooperationproj
                ->where('projOrg',"like","%$request->projOrg%"); 
        if($request->name != "")
            $cooperationproj = $cooperationproj
                ->where('name',"like","%$request->name%"); 
        if($request->projName != "")
            $cooperationproj = $cooperationproj
                ->where('projName',"like","%$request->projName%"); 
        if($request->startDate != "")
            $cooperationproj = $cooperationproj
                ->where('startDate','>=',"$request->startDate");
        if($request->endDate != "")
            $cooperationproj = $cooperationproj
                ->where('endDate','<=',"$request->endDate");
        if($request->comments != "")
            $cooperationproj = $cooperationproj
                ->where('comments',"like","%$request->comments%");

        $cooperationproj = $cooperationproj->orderBy($sortBy,$orderBy)
            ->paginate(20);
        $cooperationproj->appends($request->except('page'));    
        $user = Auth::user();
        $data = compact('cooperationproj','user');
        return view('other/cooperation_proj',$data);




    }

    public function edit($id){
        $cooperationproj = CooperationProj::find($id);
        if(Gate::allows('permission',$cooperationproj))
            return view('other/cooperation_proj_edit',$cooperationproj);
        return redirect('cooperation_proj');
    }

    public function update($id,Request $request){
        $cooperationproj = CooperationProj::find($id);
        if(!Gate::allows('permission',$cooperationproj))
            return redirect('cooperation_proj');
        $this->validate($request,[
            'college'=>'required|max:11',
            'dept'=>'required|max:11',
            'name'=>'required|max:10',
            'projName'=>'required|max:200',
            'projOrg'=>'required|max:200',
            'startDate'=>'required',
            'endDate'=>'required',
            'comments'=>'max:500',
            ]);
        $cooperationproj->update($request->all());
        return redirect('cooperation_proj')->with('success','更新成功');
    }

    
       public function delete($id){
        $cooperationproj = CooperationProj::find($id);
        if(!Gate::allows('permission',$cooperationproj))
            return redirect('cooperation_proj');
        $cooperationproj->delete();
        return redirect('cooperation_proj');
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
                        case '主辦人':
                            $item['name'] = $value;
                            unset($item[$key]);
                        case '合作機構':
                            $item['projOrg'] = $value;
                            unset($item[$key]);
                            break; 
                        case '計畫名稱':
                            $item['projName'] = $value;
                            unset($item[$key]);
                            break;
                        // case '計畫內容':
                        //     $item['projContent'] = $value;
                        //     unset($item[$key]);
                        //     break;                       
                        case '開始時間':
                            $item['startDate'] = $value;
                            unset($item[$key]);
                            break;
                        case '結束時間':
                            $item['endDate'] = $value;
                            unset($item[$key]);
                            break;
                        case '備註':
                            $item['comments'] = $value;
                            unset($item[$key]);
                            break;                        
                        default:
                            $validator = Validator::make($item,[]);
                            $validator->errors()->add('format','檔案欄位錯誤');
                            return redirect('cooperation_proj')
                                ->withErrors($validator,"upload");
                            break;
                    }
                }
                $validator = Validator::make($item,[
                    'college'=>'required|max:11',
                    'dept'=>'required|max:11',
                    'name'=>'required|max:10',
                    'projName'=>'required|max:200',
                    'projOrg'=>'required|max:200',
                    'startDate'=>'required',
                    'endDate'=>'required',
                    'comments'=>'max:500',
                ]);
                if($validator->fails()){
                    return redirect('cooperation_proj')
                        ->withErrors($validator,"upload");
                }
                if(CollegeData::where('college',$item['college'])
                        ->where('dept',$item['dept'])->first()==null){
                    $validator->errors()->add('number','系所代碼錯誤');
                    return redirect('cooperation_proj')
                                ->withErrors($validator,"upload");
                }
                if(!Gate::allows('permission',(object)$item)){
                    $validator->errors()->add('permission','無法新增未有權限之系所部門');
                    return redirect('cooperation_proj')
                                ->withErrors($validator,"upload");
                }
                array_push($newArray,$item);
            }
            CooperationProj::insert($newArray);
        });
        return redirect('cooperation_proj');
    }

    public function example(Request $request){
        return response()->download(public_path().'/Excel_example/other/cooperation_proj.xlsx',"國際合作交流計畫.xlsx");
    }
}
