<?php

namespace App\Http\Controllers\other;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\TransnationalDegree;

class TransnationalDegreeController extends Controller
{
    //
    public function index (){
    	$transnational = TransnationalDegree::paginate(20);
    	$data = compact('transnational');
    	return view ('other/transnational_degree',$data);
    }
}
