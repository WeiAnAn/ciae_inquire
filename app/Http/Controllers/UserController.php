<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\CollegeData;


class UserController extends Controller
{
	public function index(Request $request){
		
		$user = $request->user();
		$collegeData = CollegeData::where('college',$user->college)
						   ->where('dept',$user->dept)->first();
		// $collegeData = $college[0];
		$data = compact('collegeData');
		return view('user/user',$data);
	}
    //
    public function update(Request $request){
    	$id = $request->id;
    	$user = User::find($id);

    	
    	$this->validate($request,[
    		'password' => 'confirmed|max:16',
    		'chtName' => 'required|max:10',
    		'engName' => 'max:20',
    		'contactPeople' => 'required|max:10',
    		'phone' => 'max:20',
    		'email' => 'max:50',
    	]);
    	foreach ($request->request as $key => $value) {
    		# code...
    		if($value != '')
    			$newRequest[$key] = $value;
    	}
    	$request->replace($newRequest);
    	if($request->password){
    		$request->merge(array('password' => bcrypt($request->password)));
    	}
    	
    	$user->update($request->all());
    	return redirect('/user')->with('success','更新成功');
    }
}
