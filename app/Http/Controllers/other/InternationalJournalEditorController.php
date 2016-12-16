<?php

namespace App\Http\Controllers\other;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\internationalJournaleditor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Excel;
use Validator;


class InternationalJournalEditorController extends Controller
{
    public function index(Request $request){
        $sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;

        $internationaljeditor=internationalJournaleditor::
            join('college_data',function($join){
            $join->on('international_journal_editor.college','college_data.college');
            $join->on('international_journal_editor.dept','college_data.dept');
        })->orderBy($sortBy,$orderBy)
            ->paginate(20);
        $user = Auth::user();
        $data=compact('internationaljeditor','user');

    	return view ('other/international_journal_editor',$data);
    }
      public function insert(Request $request){
      
            $this->validate($request,[
            'college'=>'required|max:11',
            'dept'=>'required|max:11',
            'name'=>'required|max:20',
            'journalName'=>'required|max:200',
            'startDate'=>'required',
            'endDate'=>'required',
            'comments'=>'max:500',
            ]);
          internationalJournaleditor::create($request->all());

       return redirect('international_journal_editor')->with('success','新增成功');
    }

    public function search (Request $request){
        $sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;

        $internationaljeditor = internationalJournaleditor::join('college_data',function($join){
                $join->on('international_journal_editor.college','college_data.college');
                $join->on('international_journal_editor.dept','college_data.dept');
            });
        if($request->college != 0)
            $internationaljeditor = $internationaljeditor
                ->where('international_journal_editor.college',$request->college);
        if($request->dept != 0)
            $internationaljeditor = $internationaljeditor
                ->where('international_journal_editor.dept',$request->dept);
        if($request->name != "")
            $internationaljeditor = $internationaljeditor
                ->where('name',"like","%$request->name%"); 
        if($request->journalName != "")
            $internationaljeditor = $internationaljeditor
                ->where('journalName',"like","%$request->journalName%"); 
        if($request->startDate != "")
            $internationaljeditor = $internationaljeditor
                ->where('startDate','>=',"$request->startDate");
        if($request->endDate != "")
            $internationaljeditor = $internationaljeditor
                ->where('endDate','<=',"$request->endDate");
        if($request->comments != "")
            $internationaljeditor = $internationaljeditor
                ->where('comments',"like","%$request->comments%");

        $internationaljeditor = $internationaljeditor->orderBy($sortBy,$orderBy)
            ->paginate(20);
        $internationaljeditor->appends($request->except('page'));    
        $user = Auth::user();
        $data = compact('internationaljeditor','user');
        return view('other/international_journal_editor',$data);
    }



    public function delete($id){
        $IJE = internationalJournaleditor::find($id);
        if(!Gate::allows('permission',$IJE))
            return redirect('international_journal_editor');
        $IJE->delete();
        return redirect('international_journal_editor');
        }
}
