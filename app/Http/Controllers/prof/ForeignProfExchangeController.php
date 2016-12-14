<?php

namespace App\Http\Controllers\prof;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ForeignProfExchange;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Excel;
use Validator;
use App\CollegeData;

class ForeignProfExchangeController extends Controller
{
    
    public function index(Request $request){

    	return view ('prof/foreign_prof_exchange');
    }

    public function upload(Request $request){
        Excel::load($request->file('file'),function($reader){
            $array = $reader->toArray();
            $newArray = [];
            foreach ($array as $item) {
                foreach ($item as $key => $value) {

                    switch ($key) {
                        case '單請單位一級單位名稱':
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
                        case '外籍學者身分輸入數字':
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
                    // 'college'=>'required|max:11',
                    // 'dept'=>'required|max:11',
                    // 'nation'=>'required|max:20',
                    // 'chtName'=>'required|max:50',
                    // 'engName'=>'required|max:80',
                    // 'startDate'=>'required',
                    // 'endDate'=>'required',
                    // 'comments'=>'max:500',
                ]);
                if($validator->fails()){
                    return redirect('foreign_prof_exchange')
                        ->withErrors($validator,"upload");
                }
                if(CollegeData::where('college',$item['college'])
                        ->where('dept',$item['dept'])->first()==null){
                    $validator->errors()->add('number','系所代碼錯誤');
                    return redirect('foreign_prof_exchange')
                                ->withErrors($validator,"upload");
                }
                if(!Gate::allows('permission',(object)$item)){
                    $validator->errors()->add('permission','無法新增未有權限之系所部門');
                    return redirect('foreign_prof_exchange')
                                ->withErrors($validator,"upload");
                }
                array_push($newArray,$item);
            }
            ForeignProfVist::insert($newArray);
        });
        return redirect('foreign_prof_exchange');
    }

    public function example(Request $request){
        return response()->download(public_path().'/Excel_example/prof/foreign_prof_exchange.xlsx',"外籍學者蒞校交換.xlsx");
    }
}
