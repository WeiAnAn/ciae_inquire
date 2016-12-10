<?php

namespace App\Http\Controllers\stu;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ForeignStu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Excel;
use Validator;
use App\CollegeData;

class ForeignStuController extends Controller
{
    //
    public function index(Request $request){
    	$sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;

    	$foreignStu = ForeignStu::join('college_data',function($join){
    		$join->on('foreign_stu.college','college_data.college');
    		$join->on('foreign_stu.dept','college_data.dept');
    		})->orderBy($sortBy,$orderBy)->paginate(20);
        $foreignStu->appends($request->except('page'));  
    	$user=Auth::user();
    	$data = compact('foreignStu','user');
    	return view ('stu/foreign_stu',$data);

    }

    public function insert(Request $request){
        
        $this->validate($request,[
            'college'=>'required|max:11',
            'dept'=>'required|max:11',
            'chtName'=>'required|max:50',
            'engName'=>'required|max:50',
            'stuID'=>'required|max:15',
            'stuLevel'=>'required|max:11',
            'nation'=>'required|max:50',
            'engNation'=>'required|max:50',
            'engNation'=>'required|max:50',
            'startDate'=>'required',
            'endDate'=>'required',
            'comments'=>'max:500',
            ]);

        ForeignStu::create($request->all());

        return redirect('foreign_stu')->with('success','新增成功');
    }

    public function search (Request $request){

    	$sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;
        
        $foreignStu = ForeignStu::join('college_data',function($join){
                $join->on('foreign_stu.college','college_data.college');
                $join->on('foreign_stu.dept','college_data.dept');
            });
        if($request->college != 0)
            $foreignStu = $foreignStu
                ->where('foreign_stu.college',$request->college);
        if($request->dept != 0)
            $foreignStu = $foreignStu
                ->where('foreign_stu.dept',$request->dept);
        if($request->stuID != "")
            $foreignStu = $foreignStu
                ->where('stuID',"like","%$request->stuID%"); 
        if($request->chtName != "")
            $foreignStu = $foreignStu
                ->where('chtName',"like","%$request->chtName%"); 
        if($request->engName != "")
            $foreignStu = $foreignStu
                ->where('engName',"like","%$request->engName%");       
        if($request->stuLevel != "")
            $foreignStu = $foreignStu
                ->where('stuLevel', $request->stuLevel);                
        if($request->nation != "")
            $foreignStu = $foreignStu
                ->where('nation',"like","%$request->nation%");               
        if($request->engNation != "")
            $foreignStu = $foreignStu
                ->where('engNation',"like","%$request->engNation%");
        if($request->startDate != "")
            $foreignStu = $foreignStu
                ->where('startDate','>',"$request->startDate");
        if($request->endDate != "")
            $foreignStu = $foreignStu
                ->where('endDate','<',"$request->endDate");
        if($request->comments != "")
            $foreignStu = $foreignStu
                ->where('comments',"like","%$request->comments%");

        $foreignStu = $foreignStu->orderBy($sortBy,$orderBy)
            ->paginate(20);
        $foreignStu->appends($request->except('page'));    
        $user = Auth::user();
        $data = compact('foreignStu','user');
        return view('stu/foreign_stu',$data);
    }
        public function delete($id){
        $foreignStu = ForeignStu::find($id);
        if(!Gate::allows('permission',$foreignStu))
            return redirect('foreign_stu');
        $foreignStu->delete();
        return redirect('foreign_stu');
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
                        case '學號':
                            $item['stuID'] = $value;
                            unset($item[$key]);
                            break;
                        case '中文姓名':
                            $item['chtName'] = $value;
                            unset($item[$key]);
                            break;
                        case '英文姓名':
                            $item['engName'] = $value;
                            unset($item[$key]);
                            break;
                        case '身分':
                            $item['stuLevel'] = $value;
                            unset($item[$key]);
                            break;
                        case '中文國籍':
                            $item['nation'] = $value;
                            unset($item[$key]);
                            break;
                        case '英文國籍':
                            $item['engNation'] = $value;
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
                            return redirect('foreign_stu')
                                ->withErrors($validator,"upload");
                            break;
                    }
                }
                $validator = Validator::make($item,[
                    'college' => 'required',
                    'dept' => 'required',
                    'stuLevel' => 'required|max:200',
                    'nation' => 'required|max:200',
                    'comments' => 'max:500',
                ]);
                if($validator->fails()){
                    return redirect('foreign_stu')
                        ->withErrors($validator,"upload");
                }
                if(CollegeData::where('college',$item['college'])
                        ->where('dept',$item['dept'])->first()==null){
                    $validator->errors()->add('number','系所代碼錯誤');
                    return redirect('foreign_stu')
                                ->withErrors($validator,"upload");
                }
                if(!Gate::allows('permission',(object)$item)){
                    $validator->errors()->add('permission','無法新增未有權限之系所部門');
                    return redirect('foreign_stu')
                                ->withErrors($validator,"upload");
                }
                array_push($newArray,$item);
            }
            ForeignStu::insert($newArray);
        });
        return redirect('foreign_stu');
    }
    
     public function example(Request $request){
        return response()->download(public_path().'/Excel_example/stu/foreign_stu.xlsx',"修讀正式學位之外國學生.xlsx");
    }   			
}
