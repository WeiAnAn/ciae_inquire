<?php

namespace App\Http\Controllers\user;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\GraduateThreshold;
use Illuminate\Support\Facades\Auth;

class GraduateThresholdController extends Controller
{
    //
    public function index(){

    	$graduateThreshold = GraduateThreshold::join('college_data',function($join){
    		$join->on('graduate_threshold.college','college_data.college');
    		$join->on('graduate_threshold.dept','college_data.dept');
    	})->paginate(20);
        $user = Auth::user();

    	$data=compact('graduateThreshold','user');
    	return view ('user/graduate_threshold',$data);

    }

    public function edit($id){
        $graduateThreshold = GraduateThreshold::find($id);
        if($this->permission($graduateThreshold)){
            return view('user/graduate_threshold_edit',$graduateThreshold);
        }
        return redirect('graduate_threshold');
    }

    public function update($id,Request $request){
        $graduateThreshold = GraduateThreshold::find($id);
        if(!$this->permission($graduateThreshold))
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
        if(!$this->permission($graduateThreshold))
            return redirect('graduate_threshold');
        $graduateThreshold->delete();
        return redirect('graduate_threshold');
    }
    public function search(Request $request){

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
        if($request->testName != "")
            $graduateThreshold = $graduateThreshold
                ->where('testName',"like","%$request->testName%");
        if($request->testGrade != "")
            $graduateThreshold = $graduateThreshold
                ->where('testGrade',"like","%$request->testGrade%");
        if($request->comments != "")
            $graduateThreshold = $graduateThreshold
                ->where('comments',"like","%$request->comments%");
        $graduateThreshold = $graduateThreshold->paginate(20);
        $user = Auth::user();
        $data = compact('graduateThreshold','user');
        return view('user/graduate_threshold',$data);
    }
    public function permission(GraduateThreshold $graduateThreshold){
        $user = Auth::user();
        if(($user->permission < 2 )|| 
            ($user->permission == 2 && $user->college == $graduateThreshold->college) ||
            ($user->permission == 3 && $user->college == $graduateThreshold->college && 
            $user->dept == $graduateThreshold->dept))
            return true;
        return false;
    }

}
