<?php

namespace App\Http\Controllers\stu;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\StuForeignResearch;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Excel;
use Validator;
use App\CollegeData;


class StuForeignResearchController extends Controller
{
    //
     public function index (Request $request){
     	$sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;

    	$foreignreseach = StuForeignResearch::join('college_data',function($join){
    		$join->on('stu_foreign_research.college','college_data.college');
    		$join->on('stu_foreign_research.dept','college_data.dept');
    		})->orderBy($sortBy,$orderBy)->paginate(20);
        $foreignreseach->appends($request->except('page'));    

    	$user = Auth::user();
		$data = compact('foreignreseach','user');
		return view('stu/stu_foreign_research',$data);
    	}

    public function insert(Request $request){
            
            $this->validate($request,[
                'college'=>'required|max:11',
                'dept'=>'required|max:11',
                'name'=>'required|max:20',
                'stuLevel'=>'required|max:11',
                'nation'=>'required|max:20',
                'startDate'=>'required',
                'endDate'=>'required',
                'comments'=>'max:500',
                ]);

            StuForeignResearch::create($request->all());

            return redirect('stu_foreign_research')->with('success','新增成功');
    }

    public function search (Request $request){

    	$sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;
        
        $foreignreseach = StuForeignResearch::join('college_data',function($join){
                $join->on('stu_foreign_research.college','college_data.college');
                $join->on('stu_foreign_research.dept','college_data.dept');
            });
        if($request->college != 0)
            $foreignreseach = $foreignreseach
                ->where('stu_foreign_research.college',$request->college);
        if($request->dept != 0)
            $foreignreseach = $foreignreseach
                ->where('stu_foreign_research.dept',$request->dept);
        if($request->name != "")
            $foreignreseach = $foreignreseach
                ->where('name',"like","%$request->name%");        
        if($request->stuLevel != "")
            $foreignreseach = $foreignreseach
                ->where('stuLevel', $request->stuLevel);                
        if($request->nation != "")
            $foreignreseach = $foreignreseach
                ->where('nation',"like","%$request->nation%");
        if($request->startDate != "")
            $foreignreseach = $foreignreseach
                ->where('startDate','>',"$request->startDate");
        if($request->endDate != "")
            $foreignreseach = $foreignreseach
                ->where('endDate','<',"$request->endDate");
        if($request->comments != "")
            $foreignreseach = $foreignreseach
                ->where('comments',"like","%$request->comments%");

        $foreignreseach = $foreignreseach->orderBy($sortBy,$orderBy)
            ->paginate(20);
        $foreignreseach->appends($request->except('page'));    
        $user = Auth::user();
        $data = compact('foreignreseach','user');
        return view('stu/stu_foreign_research',$data);
    }

       public function edit($id){
        $foreignreseach = StuForeignResearch::find($id);
        if(Gate::allows('permission',$foreignreseach))
            return view('stu/stu_foreign_research_edit',$foreignreseach);
        return redirect('stu_foreign_research');
    }

    public function update($id,Request $request){
        $foreignreseach = StuForeignResearch::find($id);
        if(!Gate::allows('permission',$foreignreseach))
            return redirect('stu_foreign_research');
        $this->validate($request,[
                'college'=>'required|max:11',
                'dept'=>'required|max:11',
                'name'=>'required|max:20',
                'stuLevel'=>'required|max:11',
                'nation'=>'required|max:20',
                'startDate'=>'required',
                'endDate'=>'required',
                'comments'=>'max:500',
            ]);
        $foreignreseach->update($request->all());
        return redirect('stu_foreign_research')->with('success','更新成功');
    }

    
     public function delete($id){
        $foreignreseach = StuForeignResearch::find($id);
        if(!Gate::allows('permission',$foreignreseach))
            return redirect('stu_foreign_research');
        $foreignreseach->delete();
        return redirect('stu_foreign_research');
        }  
    
    public function upload(Request $request){
        Excel::load($request->file('file'),function($reader){
            $array = $reader->toArray();
            $newArray = [];
            foreach ($array as $item) {
                foreach ($item as $key => $value) {

                    switch ($key) {
                        case '單位名稱':
                            $item['college'] = $value;
                            unset($item[$key]);
                            break;
                        case '系所部門':
                            $item['dept'] = $value;
                            unset($item[$key]);
                            break;
                        case '姓名':
                            $item['name'] = $value;
                            unset($item[$key]);
                            break;
                        case '身分':
                            $item['stuLevel'] = $value;
                            unset($item[$key]);
                            break;
                        case '前往國家':
                            $item['nation'] = $value;
                            unset($item[$key]);
                            break;
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
                            return redirect('stu_foreign_research')
                                ->withErrors($validator,"upload");
                            break;
                    }
                }
                $validator = Validator::make($item,[
                    'college'=>'required|max:11',
                    'dept'=>'required|max:11',
                    'name'=>'required|max:20',
                    'stuLevel'=>'required|max:11',
                    'nation'=>'required|max:20',
                    'startDate'=>'required',
                    'endDate'=>'required',
                    'comments'=>'max:500',
                ]);
                if($validator->fails()){
                    return redirect('stu_foreign_research')
                        ->withErrors($validator,"upload");
                }
                if(CollegeData::where('college',$item['college'])
                        ->where('dept',$item['dept'])->first()==null){
                    $validator->errors()->add('number','系所代碼錯誤');
                    return redirect('stu_foreign_research')
                                ->withErrors($validator,"upload");
                }
                if(!Gate::allows('permission',(object)$item)){
                    $validator->errors()->add('permission','無法新增未有權限之系所部門');
                    return redirect('stu_foreign_research')
                                ->withErrors($validator,"upload");
                }
                array_push($newArray,$item);
            }
            StuForeignResearch::insert($newArray);
        });
        return redirect('stu_foreign_research');
    }
    
     public function example(Request $request){
        return response()->download(public_path().'/Excel_example/stu/stu_foreign_research.xlsx',"其他出國研修情形.xlsx");
    }
                         			
}
