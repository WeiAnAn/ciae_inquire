<?php

namespace App\Http\Controllers\prof;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ProfExchange;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Excel;
use Validator;
use App\CollegeData;

class ProfExchangeController extends Controller
{
    //
    public function index(Request $request){
     	$sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;

    	$Pexchange=ProfExchange::join('college_data',function($join){
            $join->on('prof_exchange.college','college_data.college');
            $join->on('prof_exchange.dept','college_data.dept');
        })->orderBy($sortBy,$orderBy)
            ->paginate(20);
        $user = Auth::user();
    	$data=compact('Pexchange','user');
    	return view ('prof/prof_exchange',$data);
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
            return redirect('prof_exchange')->withErrors($validator)->withInput();
        }

        if($validator->fails()){
            return redirect('prof_exchange')->withErrors($validator)->withInput();
        }

        profExchange::create($request->all());
        return redirect('prof_exchange')->with('success','新增成功');
    }

    public function search (Request $request){

    	$sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;
        
        $Pexchange = ProfExchange::join('college_data',function($join){
                $join->on('prof_exchange.college','college_data.college');
                $join->on('prof_exchange.dept','college_data.dept');
            });
        if($request->college != 0)
            $Pexchange = $Pexchange
                ->where('prof_exchange.college',$request->college);
        if($request->dept != 0)
            $Pexchange = $Pexchange
                ->where('prof_exchange.dept',$request->dept);
        if($request->name != "")
            $Pexchange = $Pexchange
                ->where('name',"like","%$request->name%");        
        if($request->profLevel != "")
            $Pexchange = $Pexchange
                ->where('profLevel', $request->profLevel);                
        if($request->nation != "")
            $Pexchange = $Pexchange
                ->where('nation',"like","%$request->nation%");
        if($request->startDate != "")
            $Pexchange = $Pexchange
                ->where('startDate','>=',"$request->startDate");
        if($request->endDate != "")
            $Pexchange = $Pexchange
                ->where('endDate','<=',"$request->endDate");        
        if($request->comments != "")
            $Pexchange = $Pexchange
                ->where('comments',"like","%$request->comments%");

        $Pexchange = $Pexchange->orderBy($sortBy,$orderBy)
            ->paginate(20);
        $Pexchange->appends($request->except('page'));    
        $user = Auth::user();
        $data = compact('Pexchange','user');
        return view('prof/prof_exchange',$data);
    }
    public function delete($id){
        $Pexchange=ProfExchange::find($id);
        if(!Gate::allows('permission',$Pexchange))
            return redirect('prof_exchange');
        $Pexchange->delete();
        return redirect('prof_exchange');
    }
    public function edit($id){
        $Pexchange = ProfExchange::find($id);
        if(Gate::allows('permission',$Pexchange))
            return view('prof/prof_exchange_edit',$Pexchange);
        return redirect('prof_exchange');
    }
    public function update($id,Request $request){
        $Pexchange = ProfExchange::find($id);
        if(!Gate::allows('permission',$Pexchange))
            return redirect('prof_exchange');
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
            return redirect("prof_exchange/$id")->withErrors($validator)->withInput();
        }

        if($validator->fails()){
            return redirect("prof_exchange/$id")->withErrors($validator)->withInput();
        }

        $Pexchange->update($request->all());
        return redirect('prof_exchange')->with('success','更新成功');
    }


    public function upload(Request $request){
        Excel::load($request->file('file'),function($reader){
            $array = $reader->toArray();
            $newArray = [];
            foreach ($array as $arrayKey => $item) {
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
                        case '身分教授副教授助理教授或博士後研究員':
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
                                    return redirect('prof_exchange')
                                        ->withErrors($validator,"upload");
                                    break;
                            }
                            $item['profLevel'] = $value;
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
                    return redirect('prof_exchange')
                        ->withErrors($validator,"upload");
                }
                if(CollegeData::where('college',$item['college'])
                        ->where('dept',$item['dept'])->first()==null){
                    $validator->errors()->add('number','系所代碼錯誤');
                    return redirect('prof_exchange')
                                ->withErrors($validator,"upload");
                }
                if(!Gate::allows('permission',(object)$item)){
                    $validator->errors()->add('permission','無法新增未有權限之系所部門');
                    return redirect('prof_exchange')
                                ->withErrors($validator,"upload");
                }
                array_push($newArray,$item);
            }
            ProfExchange::insert($newArray);
        });
        return redirect('prof_exchange');
    }

    public function example(Request $request){
        return response()->download(public_path().'/Excel_example/prof/prof_exchange.xlsx',"本校教師赴國外交換.xlsx");
    }
   
}
