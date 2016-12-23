<?php

namespace App\Http\Controllers\prof;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ProfForeignResearch;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Excel;
use Validator;
use App\CollegeData;

class ProfForeignResearchController extends Controller
{
    //
    public function index(Request $request){
    	$sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;

    	$Pforeignresearch=ProfForeignResearch::join('college_data',function($join){
            $join->on('prof_foreign_research.college','college_data.college');
            $join->on('prof_foreign_research.dept','college_data.dept');
        })->orderBy($sortBy,$orderBy)
            ->paginate(20);
        $user = Auth::user();
    	$data=compact('Pforeignresearch','user');
    	return view ('prof/prof_foreign_research',$data);
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

        if($request->startDate > $request->endDate){
            $validator = Validator::make($request->all(),[]);
            $validator->errors()->add('endDate','開始時間必須在結束時間前');
            return redirect('prof_foreign_research')->withErrors($validator)->withInput();
        }

        ProfForeignResearch::create($request->all());

        return redirect('prof_foreign_research')->with('success','新增成功');
    }

    public function search (Request $request){

    	$sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;
        
        $Pforeignresearch = ProfForeignResearch::join('college_data',function($join){
                $join->on('prof_foreign_research.college','college_data.college');
                $join->on('prof_foreign_research.dept','college_data.dept');
            });
        if($request->college != 0)
            $Pforeignresearch = $Pforeignresearch
                ->where('prof_foreign_research.college',$request->college);
        if($request->dept != 0)
            $Pforeignresearch = $Pforeignresearch
                ->where('prof_foreign_research.dept',$request->dept);
        if($request->name != "")
            $Pforeignresearch = $Pforeignresearch
                ->where('name',"like","%$request->name%");        
        if($request->profLevel != "")
            $Pforeignresearch = $Pforeignresearch
                ->where('profLevel', $request->profLevel);                
        if($request->nation != "")
            $Pforeignresearch = $Pforeignresearch
                ->where('nation',"like","%$request->nation%");
        if($request->startDate != "")
            $Pforeignresearch = $Pforeignresearch
                ->where('startDate','>=',"$request->startDate");
        if($request->endDate != "")
            $Pforeignresearch = $Pforeignresearch
                ->where('endDate','<=',"$request->endDate");
        if($request->comments != "")
            $Pforeignresearch = $Pforeignresearch
                ->where('comments',"like","%$request->comments%");

        $Pforeignresearch = $Pforeignresearch->orderBy($sortBy,$orderBy)
            ->paginate(20);
        $Pforeignresearch->appends($request->except('page'));    
        $user = Auth::user();
        $data = compact('Pforeignresearch','user');
        return view('prof/prof_foreign_research',$data);
    }
      public function delete($id){
        $Pforeignresearch = ProfForeignResearch::find($id);
        if(!Gate::allows('permission',$Pforeignresearch))
            return redirect('prof_foreign_research');
        $Pforeignresearch->delete();
        return redirect('prof_foreign_research');
    }


    public function edit($id){
        $Pforeignresearch = ProfForeignResearch::find($id);
        if(Gate::allows('permission',$Pforeignresearch))
            return view('prof/prof_foreign_research_edit',$Pforeignresearch);
        return redirect('prof_foreign_research');
    }
    public function update($id,Request $request){
        $Pforeignresearch = ProfForeignResearch::find($id);
        if(!Gate::allows('permission',$Pforeignresearch))
            return redirect('prof_foreign_research');
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
        $Pforeignresearch->update($request->all());
        return redirect('prof_foreign_research')->with('success','更新成功');
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
                                    return redirect('prof_foreign_research')
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
                    return redirect('prof_foreign_research')
                        ->withErrors($validator,"upload");
                }
                if(CollegeData::where('college',$item['college'])
                        ->where('dept',$item['dept'])->first()==null){
                    $validator->errors()->add('number','系所代碼錯誤');
                    return redirect('prof_foreign_research')
                                ->withErrors($validator,"upload");
                }
                if(!Gate::allows('permission',(object)$item)){
                    $validator->errors()->add('permission','無法新增未有權限之系所部門');
                    return redirect('prof_foreign_research')
                                ->withErrors($validator,"upload");
                }
                array_push($newArray,$item);
            }
            ProfForeignResearch::insert($newArray);
        });
        return redirect('prof_foreign_research');
    }

    public function example(Request $request){
        return response()->download(public_path().'/Excel_example/prof/prof_foreign_research.xlsx',"本校教師赴國外研究.xlsx");
    }

}
