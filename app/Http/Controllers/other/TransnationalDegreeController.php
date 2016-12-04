<?php

namespace App\Http\Controllers\other;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\TransnationalDegree;
use Illuminate\Support\Facades\Auth;

class TransnationalDegreeController extends Controller
{
    //
    public function index (Request $request){
    	$transnational = TransnationalDegree::join('college_data',function($join){
    		$join->on('transnational_degree.college','college_data.college');
    		$join->on('transnational_degree.dept','college_data.dept');
    		})->paginate(20);
    	$user = Auth::user();
    	$data = compact('transnational','user');
    	return view ('other/transnational_degree',$data);
    }

    public function search (Request $request){
    	$sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;

        $transnational = TransnationalDegree::join('college_data',function($join){
                $join->on('transnational_degree.college','college_data.college');
                $join->on('transnational_degree.dept','college_data.dept');
            });
        if($request->college != 0)
            $transnational = $transnational
                ->where('transnational_degree.college',$request->college);
        if($request->dept != 0)
            $transnational = $transnational
                ->where('transnational_degree.dept',$request->dept);        
        if($request->nation != "")
            $transnational = $transnational
                ->where('nation',"like","%$request->nation%"); 
        if($request->chtName != "")
            $transnational = $transnational
                ->where('chtName',"like","%$request->chtName%"); 
        if($request->engName != "")
            $transnational = $transnational
                ->where('engName',"like","%$request->engName%");
        if($request->bachelor != "")
            $transnational = $transnational
                ->where('bachelor',$request->bachelor);
        if($request->master != "")
            $transnational = $transnational
                ->where('master',$request->master);
        if($request->PHD != "")
            $transnational = $transnational
                ->where('PHD',$request->PHD);
        if($request->teachMode != "")
            $transnational = $transnational
                ->where('teachMode',"like","%$request->teachMode%"); 
        if($request->degreeMode != "")
            $transnational = $transnational
                ->where('degreeMode','like',"%$request->degreeMode%");
        if($request->comments != "")
            $transnational = $transnational
                ->where('comments','like',"%$request->comments%");


        $transnational = $transnational->orderBy($sortBy,$orderBy)
            ->paginate(20);
        $transnational->appends($request->except('page'));    
        $user = Auth::user();
        $data = compact('transnational','user');
        return view('other/transnational_degree',$data);
    }
}
