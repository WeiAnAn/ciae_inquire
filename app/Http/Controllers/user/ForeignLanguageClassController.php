<?php

namespace App\Http\Controllers\user;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ForeignLanguageClass;

class ForeignLanguageClassController extends Controller
{
    //
    public function index(){

    	$foreignLanguageClass = ForeignLanguageClass::paginate(20);
    	$data = compact('foreignLanguageClass');

    	return view('user/foreign_language_class',$data);
    }
}
