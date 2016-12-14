<?php

namespace App\Http\Controllers\prof;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ForeignProfExchange;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Excel;
use Validator;

class ForeignProfExchangeController extends Controller
{
    public function index(Request $request){
    	
    	return view ('prof/foreign_prof_exchange');
    }
}
