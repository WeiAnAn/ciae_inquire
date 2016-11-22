<?php

namespace App\Http\Controllers\prof;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ForeignProfVist;
class ForeignProfVistController extends Controller
{
    //
    public function index(){

    	$foreignPvist=ForeignProfVist::paginate(20);
    	$data=compact('foreignPvist');
    	return view ('prof/foreign_prof_vist',$data);
    }
}
