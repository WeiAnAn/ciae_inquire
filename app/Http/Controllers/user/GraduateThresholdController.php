<?php

namespace App\Http\Controllers\user;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\GraduateThreshold;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

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
    public function insert(Request $request){
        
        $this->validate($request,[
        
            'college'=>'required|max:200',
            'dept'=>'required|max:200',
            'testName'=>'required|max:200',
            'testGrade'=>'required|max:200',
            'comments'=>'max:500',
            ]);

        GraduateThreshold::create($request->all());

        return redirect('graduate_threshold')->with('success','新增成功');
    }
}
