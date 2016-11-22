<?php

namespace App\Http\Controllers\other;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\InternationalizeActivity;
class InternationalizeActivityController extends Controller
{
    //
    public function index(){

    	$internationalactivity= InternationalizeActivity::paginate(20);
    	$data=compact('internationalactivity');
    	return view ('other/internationalize_activity',$data);
    }
}
