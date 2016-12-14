<?php

namespace App\Http\Controllers\stu;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\StuAttendConf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Excel;
use Validator;
use App\CollegeData;

class StuAttendConfController extends Controller
{
    //
    public function index (Request $request){
    	$sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;

    	$conf = StuAttendConf::join('college_data',function($join){
    		$join->on('stu_attend_conf.college','college_data.college');
    		$join->on('stu_attend_conf.dept','college_data.dept');
    		})->orderBy($sortBy,$orderBy)->paginate(20);
    	$user = Auth::user();
    	$data=compact('conf','user');
    	return view ('stu/stu_attend_conf',$data);
    	}

    public function insert(Request $request){
            
            $this->validate($request,[
                'college'=>'required|max:11',
                'dept'=>'required|max:11',
                'name'=>'required|max:20',
                'stuLevel'=>'required|max:11',
                'nation'=>'required|max:20',
                'confName'=>'required|max:200',
                'startDate'=>'required',
                'endDate'=>'required',
                'comments'=>'max:500',
                ]);

            StuAttendConf::create($request->all());

            return redirect('stu_attend_conf')->with('success','新增成功');
        }

    public function search (Request $request){

    	$sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;
        
        $conf = StuAttendConf::join('college_data',function($join){
                $join->on('stu_attend_conf.college','college_data.college');
                $join->on('stu_attend_conf.dept','college_data.dept');
            });
        if($request->college != 0)
            $conf = $conf
                ->where('stu_attend_conf.college',$request->college);
        if($request->dept != 0)
            $conf = $conf
                ->where('stu_attend_conf.dept',$request->dept);
        if($request->name != "")
            $conf = $conf
                ->where('name',"like","%$request->name%");        
        if($request->stuLevel != "")
            $conf = $conf
                ->where('stuLevel', $request->stuLevel);                
        if($request->nation != "")
            $conf = $conf
                ->where('nation',"like","%$request->nation%");
        if($request->confName != "")
            $conf = $conf
                ->where('confName',"like","%$request->confName%"); 
        if($request->startDate != "")
            $conf = $conf
                ->where('startDate','>',"$request->startDate");
        if($request->endDate != "")
            $conf = $conf
                ->where('endDate','<',"$request->endDate"); 
        if($request->comments != "")
            $conf = $conf
                ->where('comments',"like","%$request->comments%");

        $conf = $conf->orderBy($sortBy,$orderBy)
            ->paginate(20);
        $conf->appends($request->except('page'));    
        $user = Auth::user();
        $data = compact('conf','user');
        return view('stu/stu_attend_conf',$data);
    }	
     public function delete($id){
        $conf = StuAttendConf::find($id);
        if(!Gate::allows('permission',$conf))
            return redirect('stu_attend_conf');
        $conf->delete();
        return redirect('stu_attend_conf');
    }


    public function edit($id){
        $conf = StuAttendConf::find($id);

        if(Gate::allows('permission',$conf))
            return view('stu/stu_attend_conf_edit',$conf);
        return redirect('stu_attend_conf');
    }

public function update($id,Request $request){
        $conf = StuAttendConf::find($id);
        if(!Gate::allows('permission',$conf))
            return redirect('stu_attend_conf');
        $this->validate($request,[
                'college'=>'required|max:11',
                'dept'=>'required|max:11',
                'name'=>'required|max:20',
                'stuLevel'=>'required|max:11',
                'nation'=>'required|max:20',
                'confName'=>'required|max:200',
                'startDate'=>'required',
                'endDate'=>'required',
                'comments'=>'max:500',
            ]);
        $conf->update($request->all());
        return redirect('stu_attend_conf')->with('success','更新成功');
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
                        case '會議名稱':
                            $item['confName'] = $value;
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
                            return redirect('stu_attend_conf')
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
                    'confName'=>'required|max:200',
                    'startDate'=>'required',
                    'endDate'=>'required',
                    'comments'=>'max:500',
                ]);
                if($validator->fails()){
                    return redirect('stu_attend_conf')
                        ->withErrors($validator,"upload");
                }
                if(CollegeData::where('college',$item['college'])
                        ->where('dept',$item['dept'])->first()==null){
                    $validator->errors()->add('number','系所代碼錯誤');
                    return redirect('stu_attend_conf')
                                ->withErrors($validator,"upload");
                }
                if(!Gate::allows('permission',(object)$item)){
                    $validator->errors()->add('permission','無法新增未有權限之系所部門');
                    return redirect('stu_attend_conf')
                                ->withErrors($validator,"upload");
                }
                array_push($newArray,$item);
            }
            StuAttendConf::insert($newArray);
        });
        return redirect('stu_attend_conf');
    }
    
     public function example(Request $request){
        return response()->download(public_path().'/Excel_example/stu/stu_attend_conf.xlsx',"本校學生赴國外出席國際會議.xlsx");
    }
}
