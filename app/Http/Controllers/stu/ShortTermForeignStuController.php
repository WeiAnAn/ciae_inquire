<?php

namespace App\Http\Controllers\stu;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ShortTermForeignStu;

class ShortTermForeignStuController extends Controller
{
    //
	public function index(){
		$shortterm = ShortTermForeignStu::paginate(20);
		$data = compact('shortterm');
		return view('stu/short_term_foreign_stu',$data);
	}
}
