<?php

namespace App\Http\Controllers\prof;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ProfForeignResearch;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

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
                ->where('startDate','>',"$request->startDate");
        if($request->endDate != "")
            $Pforeignresearch = $Pforeignresearch
                ->where('endDate','<',"$request->endDate");
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
}
