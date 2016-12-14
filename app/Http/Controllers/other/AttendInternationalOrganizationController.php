<?php

namespace App\Http\Controllers\other;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AttendInternationalOrganization;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Excel;
use Validator;


class AttendInternationalOrganizationController extends Controller
{
    //
    public function index(Request $request){

    	return view ('other/attend_international_organization');
	}
}
