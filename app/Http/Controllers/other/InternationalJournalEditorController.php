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
}
