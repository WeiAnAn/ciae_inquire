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
}
