<?php

namespace App\Http\Controllers\user;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ForeignLanguageClass;
use Illuminate\Support\Facades\Auth;

class ForeignLanguageClassController extends Controller
{
    //
    public function index(){

    	$foreignLanguageClass = ForeignLanguageClass::join('college_data',function($join){
    		$join->on('foreign_language_class.college','college_data.college');
    		$join->on('foreign_language_class.dept','college_data.dept');
    	})->paginate(20);
    	$user = Auth::user();
    	$data = compact('foreignLanguageClass','user');

    	return view('user/foreign_language_class',$data);
    }
/*
    public function edit($id){
    	$foreignLanguageClass = ForeignLanguageClass::find($id);
        if($this->permission($foreignLanguageClass))        {
            return view('user/graduate_threshold_edit',$foreignLanguageClass);
        }
        return redirect('foreign_language_class');


    }
*/
    public function update($id , Request $request){
    	$foreignLanguageClass = ForeignLanguageClass::find($id);
        if(!$this->permission($foreignLanguageClass))
            return redirect('graduate_threshold');
        $this->validate($request,[
            'testName' => 'required|max:200',
            'testGrade' => 'required|max:200',
            'comments' => 'max:500',
            ]);
        $foreignLanguageClass->update($request->all());
        return redirect('foreign_language_class');
    }

    public function search(Request $request){

        $foreignLanguageClass = ForeignLanguageClass::join('college_data',function($join){
    		$join->on('foreign_language_class.college','college_data.college');
    		$join->on('foreign_language_class.dept','college_data.dept');
    	});
        if($request->college != 0)
            $foreignLanguageClass = $foreignLanguageClass
                ->where('foreign_language_class.college',$request->college);
        if($request->dept != 0)
            $foreignLanguageClass = $foreignLanguageClass
                ->where('foreign_language_class.dept',$request->dept);
        if($request->year != "")
            $foreignLanguageClass = $foreignLanguageClass
                ->where('foreign_language_class.year',$request->year);
        if($request->semester != "")
            $foreignLanguageClass = $foreignLanguageClass
                ->where('foreign_language_class.semester',$request->semester);
        if($request->chtName != "")
            $foreignLanguageClass = $foreignLanguageClass
                ->where('chtName',"like","%$request->chtName%");
        if($request->engName != "")
            $foreignLanguageClass = $foreignLanguageClass
                ->where('engName',"like","%$request->engName%");
        if($request->teacher != "")
            $foreignLanguageClass = $foreignLanguageClass
                ->where('teacher',"like","%$request->teacher%");
        if($request->totalCount !="")
            $foreignLanguageClass = $foreignLanguageClass
                ->where('foreign_language_class.totalCount',$request->totalCount);
        if($request->nationalCount !="")
            $foreignLanguageClass = $foreignLanguageClass
                ->where('foreign_language_class.nationalCount',$request->nationalCount);        


        $foreignLanguageClass = $foreignLanguageClass->orderBy('id','desc')
            ->paginate(20);
        $foreignLanguageClass->appends($request->except('page'));  
        $user = Auth::user();
        $data = compact('foreignLanguageClass','user');
        return view('user/foreign_language_class',$data);
    }
}
