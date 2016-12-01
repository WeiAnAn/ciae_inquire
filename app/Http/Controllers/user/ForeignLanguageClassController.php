<?php

namespace App\Http\Controllers\user;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ForeignLanguageClass;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

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

    public function edit($id){
    	$foreignLanguageClass = ForeignLanguageClass::find($id);
        if(Gate::allows('permission',$foreignLanguageClass)){
            return view('user/foreign_language_class_edit',$foreignLanguageClass);
        }
        return redirect('foreign_language_class');


    }

    public function update($id , Request $request){
    	$foreignLanguageClass = ForeignLanguageClass::find($id);
        if(!Gate::allows('permission',$foreignLanguageClass))
            return redirect('foreign_language_class');
        $this->validate($request,[
            'year' => 'required',
            'chtName' => 'required|max:50',
            'engName' => 'required|max:200',
            'teacher' => 'required|max:20',
            'language' => 'required|max:20',
            'totalCount' => 'required',
            'nationalCount' => 'required',
            ]);
        $foreignLanguageClass->update($request->all());
        return redirect('foreign_language_class');
    }
    public function delete($id){
        $foreignLanguageClass = ForeignLanguageClass::find($id);
        if(!Gate::allows('permission',$foreignLanguageClass))
            return redirect('foreign_language_class');
        $foreignLanguageClass->delete();
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
