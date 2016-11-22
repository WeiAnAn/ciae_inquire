<?php

namespace App\Http\Controllers\stu;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\StuForeignResearch;

class StuForeignResearchController extends Controller
{
    //
     public function index (){
    	$foreignreseach = StuForeignResearch::paginate(20);
		$data = compact('foreignreseach');
		return view('stu/stu_foreign_research',$data);
    	}
}
