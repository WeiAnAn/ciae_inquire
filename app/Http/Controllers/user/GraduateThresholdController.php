<?php

namespace App\Http\Controllers\user;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\GraduateThreshold;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use Validator;

class GraduateThresholdController extends Controller
{
    //
    public function index(Request $request){
        $sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;
    	$graduateThreshold = GraduateThreshold::join('college_data',function($join){
    		$join->on('graduate_threshold.college','college_data.college');
    		$join->on('graduate_threshold.dept','college_data.dept');
    	})->orderBy($sortBy,$orderBy)
            ->paginate(20);
        $user = Auth::user();
    	$data=compact('graduateThreshold','user');
    	return view ('user/graduate_threshold',$data);

    }

    public function edit($id){
        $graduateThreshold = GraduateThreshold::find($id);
        if(Gate::allows('permission',$graduateThreshold))
            return view('user/graduate_threshold_edit',$graduateThreshold);
        return redirect('graduate_threshold');
    }

    public function update($id,Request $request){
        $graduateThreshold = GraduateThreshold::find($id);
        if(!Gate::allows('permission',$graduateThreshold))
            return redirect('graduate_threshold');
        $this->validate($request,[
            'testName' => 'required|max:200',
            'testGrade' => 'required|max:200',
            'comments' => 'max:500',
            ]);
        $graduateThreshold->update($request->all());
        return redirect('graduate_threshold');
    }

    public function delete($id){
        $graduateThreshold = GraduateThreshold::find($id);
        if(!Gate::allows('permission',$graduateThreshold))
            return redirect('graduate_threshold');
        $graduateThreshold->delete();
        return redirect('graduate_threshold');
    }
    public function search(Request $request){
        $sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;
        
        $graduateThreshold = GraduateThreshold::join('college_data',function($join){
                $join->on('graduate_threshold.college','college_data.college');
                $join->on('graduate_threshold.dept','college_data.dept');
            });
        

        if($request->college != 0)
            $graduateThreshold = $graduateThreshold
                ->where('graduate_threshold.college',$request->college);
        if($request->dept != 0)
            $graduateThreshold = $graduateThreshold
                ->where('graduate_threshold.dept',$request->dept);
        if($request->semester != "")
            $graduateThreshold = $graduateThreshold
                ->where('testName',"like","%$request->testName%");
        if($request->testGrade != "")
            $graduateThreshold = $graduateThreshold
                ->where('testGrade',"like","%$request->testGrade%");
        if($request->comments != "")
            $graduateThreshold = $graduateThreshold
                ->where('comments',"like","%$request->comments%");
        $graduateThreshold = $graduateThreshold->orderBy($sortBy,$orderBy)
            ->paginate(20);

        $graduateThreshold->appends($request->except('page'));
        
        $user = Auth::user();
        $data = compact('graduateThreshold','user');
        return view('user/graduate_threshold',$data);
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
                        case '語言測驗名稱':
                            $item['testName'] = $value;
                            unset($item[$key]);
                            break;
                        case '等級或分數':
                            $item['testGrade'] = $value;
                            unset($item[$key]);
                            break;
                        case '備註':
                            $item['comments'] = $value;
                            unset($item[$key]);
                            break;
                        default:
                            break;
                    }
                    dd($key);

                }
                $validator = Validator::make($item,[
                    'college' => 'required',
                ]);
                if($validator->fails()){
                    return redirect('graduate_threshold')
                        ->withErrors($validator,"upload");
                }
                array_push($newArray,$item);
            }
            GraduateThreshold::insert($newArray);
        });
        return redirect('graduate_threshold');
    }
}
