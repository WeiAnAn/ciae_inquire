<?php

namespace App\Http\Controllers\prof;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ProfExchange;
class ProfExchangeController extends Controller
{
    //
     public function index(){

    	$Pexchange=ProfExchange::paginate(20);
    	$data=compact('Pexchange');
    	return view ('prof/prof_exchange',$data);
    }
}
