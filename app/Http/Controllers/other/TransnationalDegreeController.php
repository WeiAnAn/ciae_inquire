<?php

namespace App\Http\Controllers\other;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\TransnationalDegree;

class TransnationalDegreeController extends Controller
{
    //
    public function index (){
    	$transnational = TransnationalDegree::join('college_data',function($join){
    		$join->on('transnational_degree.college','college_data.college');
    		$join->on('transnational_degree.dept','college_data.dept');
    		})->paginate(20);

    	$data = compact('transnational');
    	return view ('other/transnational_degree',$data);
    }
}
