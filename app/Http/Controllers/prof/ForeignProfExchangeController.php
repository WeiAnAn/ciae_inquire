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
        $sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;

        $foreignPexchange=ForeignProfExchange::
            join('college_data',function($join){
            $join->on('foreign_prof_exchange.college','college_data.college');
            $join->on('foreign_prof_exchange.dept','college_data.dept');
        })->orderBy($sortBy,$orderBy)
            ->paginate(20);
        $user = Auth::user();
        $data=compact('foreignPexchange','user');


    	return view ('prof/foreign_prof_exchange',$data);
    }

    public function insert(Request $request){
      
            $this->validate($request,[
            'college'=>'required|max:11',
            'dept'=>'required|max:11',
            'name'=>'required|max:20',
            'profLevel'=>'required|max:11',
            'nation'=>'required|max:20',
            'startDate'=>'required',
            'endDate'=>'required',
            'comments'=>'max:500',
            ]);
          foreignprofexchange::create($request->all());

       return redirect('foreign_prof_exchange')->with('success','新增成功');
    }

    public function search (Request $request){
        $sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;

        $foreignPexchange = ForeignProfExchange::join('college_data',function($join){
                $join->on('foreign_prof_exchange.college','college_data.college');
                $join->on('foreign_prof_exchange.dept','college_data.dept');
            });
        if($request->college != 0)
            $foreignPexchange = $foreignPexchange
                ->where('foreign_prof_exchange.college',$request->college);
        if($request->dept != 0)
            $foreignPexchange = $foreignPexchange
                ->where('foreign_prof_exchange.dept',$request->dept);
        if($request->name != "")
            $foreignPexchange = $foreignPexchange
                ->where('name',"like","%$request->name%"); 
        if($request->profLevel != "")
            $foreignPexchange = $foreignPexchange
                ->where('profLevel',$request->profLevel);
        if($request->nation != "")
            $foreignPexchange = $foreignPexchange
                ->where('nation',"like","%$request->nation%"); 
        if($request->startDate != "")
            $foreignPexchange = $foreignPexchange
                ->where('startDate','>=',"$request->startDate");
        if($request->endDate != "")
            $foreignPexchange = $foreignPexchange
                ->where('endDate','<=',"$request->endDate");
        if($request->comments != "")
            $foreignPexchange = $foreignPexchange
                ->where('comments',"like","%$request->comments%");

        $foreignPexchange = $foreignPexchange->orderBy($sortBy,$orderBy)
            ->paginate(20);
        $foreignPexchange->appends($request->except('page'));    
        $user = Auth::user();
        $data = compact('foreignPexchange','user');
        return view('prof/foreign_prof_exchange',$data);
    }

    public function edit($id){
        $foreignPexchange = ForeignProfexchange::find($id);
        if(Gate::allows('permission',$foreignPexchange))
            return view('prof/foreign_prof_exchange_edit',$foreignPexchange);
        return redirect('foreign_prof_exchange');
    }

    public function update($id,Request $request){
        $foreignPexchange = ForeignProfExchange::find($id);
        if(!Gate::allows('permission',$foreignPexchange))
            return redirect('foreign_prof_exchange');
        $this->validate($request,[
            'college'=>'required|max:11',
            'dept'=>'required|max:11',
            'name'=>'required|max:20',
            'profLevel'=>'required|max:11',
            'nation'=>'required|max:20',
            'startDate'=>'required',
            'endDate'=>'required',
            'comments'=>'max:500',
            ]);
        $foreignPexchange->update($request->all());
        return redirect('foreign_prof_exchange')->with('success','更新成功');
    }

      public function delete($id){
        $AIO = foreignprofexchange::find($id);
        if(!Gate::allows('permission',$AIO))
            return redirect('foreign_prof_exchange');
        $AIO->delete();
        return redirect('foreign_prof_exchange');
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
