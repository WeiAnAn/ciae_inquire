<?php

namespace App\Http\Controllers\prof;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ForeignProfVist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Excel;
use Validator;
use App\CollegeData;

class ForeignProfVistController extends Controller
{
    //
    public function index(Request $request){
    	$sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;

    	$foreignPvist=ForeignProfVist::join('college_data',function($join){
            $join->on('foreign_prof_vist.college','college_data.college');
            $join->on('foreign_prof_vist.dept','college_data.dept');
        })->orderBy($sortBy,$orderBy)
            ->paginate(20);
        $user = Auth::user();
    	$data=compact('foreignPvist','user');
    	return view ('prof/foreign_prof_vist',$data);
    }

    public function insert(Request $request){
        
        $rules=[
            'college'=>'required|max:11',
            'dept'=>'required|max:11',
            'name'=>'required|max:20',
            'profLevel'=>'required|max:11',
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
            return redirect('foreign_prof_vist')->withErrors($validator)->withInput();
        }

        if($validator->fails()){
            return redirect('foreign_prof_vist')->withErrors($validator)->withInput();
        }

        ForeignProfVist::create($request->all());
        return redirect('foreign_prof_vist')->with('success','新增成功');
    }

    public function search (Request $request){

    	$sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;
        
        $foreignPvist = ForeignProfVist::join('college_data',function($join){
                $join->on('foreign_prof_vist.college','college_data.college');
                $join->on('foreign_prof_vist.dept','college_data.dept');
            });
        if($request->college != 0)
            $foreignPvist = $foreignPvist
                ->where('foreign_prof_vist.college',$request->college);
        if($request->dept != 0)
            $foreignPvist = $foreignPvist
                ->where('foreign_prof_vist.dept',$request->dept);
        if($request->name != "")
            $foreignPvist = $foreignPvist
                ->where('name',"like","%$request->name%");        
        if($request->profLevel != "")
            $foreignPvist = $foreignPvist
                ->where('profLevel', $request->profLevel);                
        if($request->nation != "")
            $foreignPvist = $foreignPvist
                ->where('nation',"like","%$request->nation%");
        if($request->startDate != "")
            $foreignPvist = $foreignPvist
                ->where('startDate','>=',"$request->startDate");
        if($request->endDate != "")
            $foreignPvist = $foreignPvist
                ->where('endDate','<=',"$request->endDate");
        if($request->comments != "")
            $foreignPvist = $foreignPvist
                ->where('comments',"like","%$request->comments%");

        $foreignPvist = $foreignPvist->orderBy($sortBy,$orderBy)
            ->paginate(20);
        $foreignPvist->appends($request->except('page'));    
        $user = Auth::user();
        $data = compact('foreignPvist','user');
        return view('prof/foreign_prof_vist',$data);
    }


    public function edit($id){
        $foreignPvist = ForeignProfVist::find($id);
        if(Gate::allows('permission',$foreignPvist))
            return view('prof/foreign_prof_vist_edit',$foreignPvist);
        return redirect('foreign_prof_vist');
    }

    public function update($id,Request $request){
        $foreignPvist = ForeignProfVist::find($id);
        if(!Gate::allows('permission',$foreignPvist))
            return redirect('foreign_prof_vist');
        $rules=[
            'college'=>'required|max:11',
            'dept'=>'required|max:11',
            'name'=>'required|max:20',
            'profLevel'=>'required|max:11',
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
            return redirect("foreign_prof_vist/$id")->withErrors($validator)->withInput();
        }

        if($validator->fails()){
            return redirect("foreign_prof_vist/$id")->withErrors($validator)->withInput();
        }

        $foreignPvist->update($request->all());
        return redirect('foreign_prof_vist')->with('success','更新成功');
    }
    public function delete($id){
        $foreignPvist = ForeignProfVist::find($id);
        if(!Gate::allows('permission',$foreignPvist))
            return redirect('foreign_prof_vist');
        $foreignPvist->delete();
        return redirect('foreign_prof_vist');
    }

    public function upload(Request $request){
        Excel::load($request->file('file'),function($reader){
            $array = $reader->toArray();
            $newArray = [];
          foreach ($array as $arrayKey => $item) {
                foreach ($item as $key => $value) {

                    switch ($key) {
                        case '邀請單位一級單位名稱':
                            $item['college'] = $value;
                            unset($item[$key]);
                            break;
                        case '邀請單位二級單位名稱':
                            $item['dept'] = $value;
                            unset($item[$key]);
                            break;
                        case '外籍學者姓名':
                            $item['name'] = $value;
                            unset($item[$key]);
                            break;
                        case '外籍學者身分教授副教授助理教授或博士後研究員':
                            switch($value){
                                case "教授":
                                    $value = 1;
                                    break;
                                case "副教授":
                                    $value = 2;
                                    break;
                                case "助理教授":
                                    $value = 3;
                                    break;
                                case "博士後研究員":
                                    $value = 4;
                                    break;
                                default:
                                    $validator = Validator::make($item,[]);
                                    $errorLine = $arrayKey + 2;
                                    $validator->errors()->add('身分',"身分內容填寫錯誤,第 $errorLine 行");
                                    return redirect('foreign_prof_vist')
                                        ->withErrors($validator,"upload");
                                    break;
                            }
                            $item['profLevel'] = $value;
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
                            break;
                    }
                }
                $validator = Validator::make($item,[
                    'college'=>'required|max:11',
                    'dept'=>'required|max:11',
                    'name'=>'required|max:20',
                    'profLevel'=>'required|max:11',
                    'nation'=>'required|max:20',
                    'startDate'=>'required',
                    'endDate'=>'required',
                    'comments'=>'max:500',
                ]);
                if($validator->fails()){
                    return redirect('foreign_prof_vist')
                        ->withErrors($validator,"upload");
                }
                if(CollegeData::where('college',$item['college'])
                        ->where('dept',$item['dept'])->first()==null){
                    $validator->errors()->add('number','系所代碼錯誤');
                    return redirect('foreign_prof_vist')
                                ->withErrors($validator,"upload");
                }
                if(!Gate::allows('permission',(object)$item)){
                    $validator->errors()->add('permission','無法新增未有權限之系所部門');
                    return redirect('foreign_prof_vist')
                                ->withErrors($validator,"upload");
                }
                array_push($newArray,$item);
            }
            ForeignProfVist::insert($newArray);
        });
        return redirect('foreign_prof_vist');
    }

    public function example(Request $request){
        return response()->download(public_path().'/Excel_example/prof/foreign_prof_vist.xlsx',"外籍學者蒞校訪問.xlsx");
    }
}
