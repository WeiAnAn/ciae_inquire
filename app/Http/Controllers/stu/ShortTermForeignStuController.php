<?php

namespace App\Http\Controllers\stu;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ShortTermForeignStu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Excel;
use Validator;
use App\CollegeData;

class ShortTermForeignStuController extends Controller
{
    //
	public function index(Request $request){
		$sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;

		$shortterm = ShortTermForeignStu::join('college_data',function($join){
    		$join->on('short_term_foreign_stu.college','college_data.college');
    		$join->on('short_term_foreign_stu.dept','college_data.dept');
    		})->orderBy($sortBy,$orderBy)->paginate(20);
        $shortterm->appends($request->except('page'));  
		$user = Auth::user();
		$data = compact('shortterm','user');
		return view('stu/short_term_foreign_stu',$data);
	}

    public function insert(Request $request){
        
        $this->validate($request,[
            'college'=>'required|max:11',
            'dept'=>'required|max:11',
            'name'=>'required|max:50',
            'stuLevel'=>'required|max:11',
            'nation'=>'required|max:50',
            'startDate'=>'required',
            'endDate'=>'required',
            'comments'=>'max:500',
            ]);

        if($request->startDate > $request->endDate){
            $validator = Validator::make($request->all(),[]);
            $validator->errors()->add('endDate','開始時間必須在結束時間前');
            return redirect('short_term_foreign_stu')->withErrors($validator)->withInput();
        }

        ShortTermForeignStu::create($request->all());

        return redirect('short_term_foreign_stu')->with('success','新增成功');
    }

	public function search (Request $request){

    	$sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;
        
        $shortterm = ShortTermForeignStu::join('college_data',function($join){
                $join->on('short_term_foreign_stu.college','college_data.college');
                $join->on('short_term_foreign_stu.dept','college_data.dept');
            });
        if($request->college != 0)
            $shortterm = $shortterm
                ->where('short_term_foreign_stu.college',$request->college);
        if($request->dept != 0)
            $shortterm = $shortterm
                ->where('short_term_foreign_stu.dept',$request->dept);
        if($request->name != "")
            $shortterm = $shortterm
                ->where('name',"like","%$request->name%");        
        if($request->stuLevel != "")
            $shortterm = $shortterm
                ->where('stuLevel', $request->stuLevel);                
        if($request->nation != "")
            $shortterm = $shortterm
                ->where('nation',"like","%$request->nation%");
        if($request->startDate != "")
            $shortterm = $shortterm
                ->where('startDate','>=',"$request->startDate");
        if($request->endDate != "")
            $shortterm = $shortterm
                ->where('endDate','<=',"$request->endDate");
        if($request->comments != "")
            $shortterm = $shortterm
                ->where('comments',"like","%$request->comments%");

        $shortterm = $shortterm->orderBy($sortBy,$orderBy)
            ->paginate(20);
        $shortterm->appends($request->except('page'));    
        $user = Auth::user();
        $data = compact('shortterm','user');
        return view('stu/short_term_foreign_stu',$data);
    }

    public function edit($id){
        $shortterm = ShortTermForeignStu::find($id);
        if(Gate::allows('permission',$shortterm))
            return view('stu/short_term_foreign_stu_edit',$shortterm);
        return redirect('short_term_foreign_stu');
    }

    public function update($id,Request $request){
        $shortterm = ShortTermForeignStu::find($id);
        if(!Gate::allows('permission',$shortterm))
            return redirect('short_term_foreign_stu');
        $this->validate($request,[
            'college'=>'required|max:11',
            'dept'=>'required|max:11',
            'name'=>'required|max:50',
            'stuLevel'=>'required|max:11',
            'nation'=>'required|max:50',
            'startDate'=>'required',
            'endDate'=>'required',
            'comments'=>'max:500',
            ]);
        $shortterm->update($request->all());
        return redirect('short_term_foreign_stu')->with('success','更新成功');
    }


    public function delete($id){
        $shortterm = ShortTermForeignStu::find($id);
        if(!Gate::allows('permission',$shortterm))
            return redirect('short_term_foreign_stu');
        $shortterm->delete();
        return redirect('short_term_foreign_stu');
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
                        case '姓名':
                            $item['name'] = $value;
                            unset($item[$key]);
                            break;
                        case '身分學士碩士或博士班':                            
                            switch($value){
                                case "學士":
                                    $value = 3;
                                    break;
                                case "碩士":
                                    $value = 2;
                                    break;
                                case "博士":
                                    $value = 1;
                                    break;
                                default:
                                    $validator = Validator::make($item,[]);
                                    $errorLine = $arrayKey + 2;
                                    $validator->errors()->add('身分',"身分內容填寫錯誤,第 $errorLine 行");
                                    return redirect('short_term_foreign_stu')
                                        ->withErrors($validator,"upload");
                                    break;
                            }
                            $item['stuLevel'] = $value;
                            unset($item[$key]);
                            break;
                        case '國籍':
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
                            return redirect('short_term_foreign_stu')
                                ->withErrors($validator,"upload");
                            break;
                    }
                }
                $validator = Validator::make($item,[
                    'college'=>'required|max:11',
                    'dept'=>'required|max:11',
                    'name'=>'required|max:50',
                    'stuLevel'=>'required|max:11',
                    'nation'=>'required|max:50',
                    'startDate'=>'required',
                    'endDate'=>'required',
                    'comments'=>'max:500',
                ]);
                if($validator->fails()){
                    return redirect('short_term_foreign_stu')
                        ->withErrors($validator,"upload");
                }
                if(CollegeData::where('college',$item['college'])
                        ->where('dept',$item['dept'])->first()==null){
                    $validator->errors()->add('number','系所代碼錯誤');
                    return redirect('short_term_foreign_stu')
                                ->withErrors($validator,"upload");
                }
                if(!Gate::allows('permission',(object)$item)){
                    $validator->errors()->add('permission','無法新增未有權限之系所部門');
                    return redirect('short_term_foreign_stu')
                                ->withErrors($validator,"upload");
                }
                array_push($newArray,$item);
            }
            ShortTermForeignStu::insert($newArray);
        });
        return redirect('short_term_foreign_stu');
    }
    
     public function example(Request $request){
        return response()->download(public_path().'/Excel_example/stu/short_term_foreign_stu.xlsx',"外籍學生至本校短期交流訪問或實習.xlsx");
    }                   			
}
