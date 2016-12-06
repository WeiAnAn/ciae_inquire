<?php

namespace App\Http\Controllers\prof;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ProfExchange;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ProfExchangeController extends Controller
{
    //
    public function index(Request $request){
     	$sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;

    	$Pexchange=ProfExchange::join('college_data',function($join){
            $join->on('prof_exchange.college','college_data.college');
            $join->on('prof_exchange.dept','college_data.dept');
        })->orderBy($sortBy,$orderBy)
            ->paginate(20);
        $user = Auth::user();
    	$data=compact('Pexchange','user');
    	return view ('prof/prof_exchange',$data);
    }

    public function insert(Request $request){
        
        $this->validate($request,[
            'college'=>'required|max:11',
            'dept'=>'required|max:11',
            'name'=>'required|max:20',
            'profLevel'=>'required|max:11',
            'nation'=>'required|max:20',
            'startDate'=>'required',
            'endDate'=>'required',
            'comments'=>'max:500',
            ]);

        profExchange::create($request->all());

        return redirect('prof_exchange')->with('success','新增成功');
    }

    public function search (Request $request){

    	$sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;
        
        $Pexchange = ProfExchange::join('college_data',function($join){
                $join->on('prof_exchange.college','college_data.college');
                $join->on('prof_exchange.dept','college_data.dept');
            });
        if($request->college != 0)
            $Pexchange = $Pexchange
                ->where('prof_exchange.college',$request->college);
        if($request->dept != 0)
            $Pexchange = $Pexchange
                ->where('prof_exchange.dept',$request->dept);
        if($request->name != "")
            $Pexchange = $Pexchange
                ->where('name',"like","%$request->name%");        
        if($request->profLevel != "")
            $Pexchange = $Pexchange
                ->where('profLevel', $request->profLevel);                
        if($request->nation != "")
            $Pexchange = $Pexchange
                ->where('nation',"like","%$request->nation%");
        if($request->startDate != "")
            $Pexchange = $Pexchange
                ->where('startDate','>',"$request->startDate");
        if($request->endDate != "")
            $Pexchange = $Pexchange
                ->where('endDate','<',"$request->endDate");        
        if($request->comments != "")
            $Pexchange = $Pexchange
                ->where('comments',"like","%$request->comments%");

        $Pexchange = $Pexchange->orderBy($sortBy,$orderBy)
            ->paginate(20);
        $Pexchange->appends($request->except('page'));    
        $user = Auth::user();
        $data = compact('Pexchange','user');
        return view('prof/prof_exchange',$data);
    }
    public function delete($id){
        $Pexchange=ProfExchange::find($id);
        if(!Gate::allows('permission',$Pexchange))
            return redirect('prof_exchange');
        $Pexchange->delete();
        return redirect('prof_exchange');
    }
    public function edit($id){
        $Pexchange = ProfExchange::find($id);
        if(Gate::allows('permission',$Pexchange))
            return view('prof/prof_exchange_edit',$Pexchange);
        return redirect('prof_exchange');
    }
    public function update($id,Request $request){
        $Pexchange = ProfExchange::find($id);
        if(!Gate::allows('permission',$Pexchange))
            return redirect('prof_exchange');
        $this->validate($request,[
            'college'=>'required|max:11',
            'dept'=>'required|max:11',
            'name'=>'required|max:20',
            'profLevel'=>'required|max:11',
            'nation'=>'required|max:20',
            'startDate'=>'required',
            'endDate'=>'required',
            'comments'=>'max:500',
            ]);
        $Pexchange->update($request->all());
        return redirect('prof_exchange')->with('success','更新成功');
    }
   
}
