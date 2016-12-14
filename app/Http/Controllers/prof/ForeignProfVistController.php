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
        
        $this->validate($request,[
            'college'=>'required|max:11',
            'dept'=>'required|max:11',
            'name'=>'required|max:50',
            'profLevel'=>'required|max:11',
            'nation'=>'required|max:20',
            'startDate'=>'required',
            'endDate'=>'required',
            'comments'=>'max:500',
            ]);

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
                ->where('startDate','>',"$request->startDate");
        if($request->endDate != "")
            $foreignPvist = $foreignPvist
                ->where('endDate','<',"$request->endDate");
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
            foreach ($array as $item) {
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
