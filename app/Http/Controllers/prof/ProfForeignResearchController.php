<?php

namespace App\Http\Controllers\prof;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ProfForeignResearch;
class ProfForeignResearchController extends Controller
{
    //
    public function index(){

    	$Pforeignresearch=ProfForeignResearch::paginate(20);
    	$data=compact('Pforeignresearch');
    	return view ('prof/prof_foreign_research',$data);
    }
}
