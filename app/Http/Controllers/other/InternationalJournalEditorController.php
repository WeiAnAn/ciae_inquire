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
    //
    public function index(Request $request){

    	return view ('other/international_journal_editor');
	}
}
