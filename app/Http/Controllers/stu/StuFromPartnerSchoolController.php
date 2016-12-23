<?php

namespace App\Http\Controllers\stu;

use Illuminate\Http\Request;
use App\StuFromPartnerSchool;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Excel;
use Validator;
use App\CollegeData;

class StuFromPartnerSchoolController extends Controller
{
    //
    public function index(Request $request){
    	$sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;

		$frompartnerdata = StuFromPartnerSchool::join('college_data',function($join){
    		$join->on('stu_from_partner_school.college','college_data.college');
    		$join->on('stu_from_partner_school.dept','college_data.dept');
    		})->orderBy($sortBy,$orderBy)->paginate(20);
        $frompartnerdata->appends($request->except('page'));  

		$user = Auth::user();
		$data = compact('frompartnerdata','user');
		return view('stu/stu_from_partner_school',$data);
	}

    public function insert(Request $request){

        $rules=[
            'college'=>'required|max:11',
            'dept'=>'required|max:11',
            'name'=>'required|max:20',
            'stuLevel'=>'required|max:11',
            'nation'=>'required|max:20',
            'startDate'=>'required',
            'endDate'=>'required',
            'comments'=>'max:500',
        ];

        $message=[
            'required'=>'必須填寫:attribute欄位',
            'max'=>':attribute欄位的輸入長度不能大於:max',
        ];

        $validator=Validator::make($request->all(),$rules,$message);

        if($request->startDate > $request->endDate){
            $validator->errors()->add('endDate','開始時間必須在結束時間前');
            return redirect('stu_from_partner_school')->withErrors($validator)->withInput();
        }

        if($validator->fails()){
            return redirect('stu_from_partner_school')->withErrors($validator)->withInput();
        }

        StuFromPartnerSchool::create($request->all());
        return redirect('stu_from_partner_school')->with('success','新增成功');
    }

	public function search (Request $request){

    	$sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;
        
        $frompartnerdata = StuFromPartnerSchool::join('college_data',function($join){
                $join->on('stu_from_partner_school.college','college_data.college');
                $join->on('stu_from_partner_school.dept','college_data.dept');
            });
        if($request->college != 0)
            $frompartnerdata = $frompartnerdata
                ->where('stu_from_partner_school.college',$request->college);
        if($request->dept != 0)
            $frompartnerdata = $frompartnerdata
                ->where('stu_from_partner_school.dept',$request->dept);
        if($request->name != "")
            $frompartnerdata = $frompartnerdata
                ->where('name',"like","%$request->name%");        
        if($request->stuLevel != "")
            $frompartnerdata = $frompartnerdata
                ->where('stuLevel', $request->stuLevel);                
        if($request->nation != "")
            $frompartnerdata = $frompartnerdata
                ->where('nation',"like","%$request->nation%");
        if($request->startDate != "")
            $frompartnerdata = $frompartnerdata
                ->where('startDate','>=',"$request->startDate");
        if($request->endDate != "")
            $frompartnerdata = $frompartnerdata
                ->where('endDate','<=',"$request->endDate");
        if($request->comments != "")
            $frompartnerdata = $frompartnerdata
                ->where('comments',"like","%$request->comments%");

        $frompartnerdata = $frompartnerdata->orderBy($sortBy,$orderBy)
            ->paginate(20);
        $frompartnerdata->appends($request->except('page'));    
        $user = Auth::user();
        $data = compact('frompartnerdata','user');
        return view('stu/stu_from_partner_school',$data);
    }

    public function edit($id){
        $frompartnerdata = StuFromPartnerSchool::find($id);
        if(Gate::allows('permission',$frompartnerdata))
            return view('stu/stu_from_partner_school_edit',$frompartnerdata);
        return redirect('stu_from_partner_school');
    }

    public function update($id,Request $request){
        $frompartnerdata = StuFromPartnerSchool::find($id);
        if(!Gate::allows('permission',$frompartnerdata))
            return redirect('stu_from_partner_school');
        $rules=[
            'college'=>'required|max:11',
            'dept'=>'required|max:11',
            'name'=>'required|max:20',
            'stuLevel'=>'required|max:11',
            'nation'=>'required|max:20',
            'startDate'=>'required',
            'endDate'=>'required',
            'comments'=>'max:500',
        ];

        $message=[
            'required'=>'必須填寫:attribute欄位',
            'max'=>':attribute欄位的輸入長度不能大於:max',
        ];

        $validator=Validator::make($request->all(),$rules,$message);

        if($request->startDate > $request->endDate){
            $validator->errors()->add('endDate','開始時間必須在結束時間前');
            return redirect("stu_from_partner_school/$id")->withErrors($validator)->withInput();
        }

        if($validator->fails()){
            return redirect("stu_from_partner_school/$id")->withErrors($validator)->withInput();
        }

        $frompartnerdata->update($request->all());
        return redirect('stu_from_partner_school')->with('success','更新成功');
    }

       public function delete($id){
        $frompartnerdata = StuFromPartnerSchool::find($id);
        if(!Gate::allows('permission',$frompartnerdata))
            return redirect('stu_from_partner_school');
        $frompartnerdata->delete();
        return redirect('stu_from_partner_school');
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
                                    return redirect('stu_from_partner_school')
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
                            return redirect('stu_from_partner_school')
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
                    return redirect('stu_from_partner_school')
                        ->withErrors($validator,"upload");
                }
                if(CollegeData::where('college',$item['college'])
                        ->where('dept',$item['dept'])->first()==null){
                    $validator->errors()->add('number','系所代碼錯誤');
                    return redirect('stu_from_partner_school')
                                ->withErrors($validator,"upload");
                }
                if(!Gate::allows('permission',(object)$item)){
                    $validator->errors()->add('permission','無法新增未有權限之系所部門');
                    return redirect('stu_from_partner_school')
                                ->withErrors($validator,"upload");
                }
                array_push($newArray,$item);
            }
            StuFromPartnerSchool::insert($newArray);
        });
        return redirect('stu_from_partner_school');
    }
    
     public function example(Request $request){
        return response()->download(public_path().'/Excel_example/stu/stu_from_partner_school.xlsx',"姊妹校學生至本校參加交換計畫.xlsx");
    }    			
}
