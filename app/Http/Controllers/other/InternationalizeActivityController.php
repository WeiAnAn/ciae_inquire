<?php

namespace App\Http\Controllers\other;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\InternationalizeActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Excel;
use Validator;

class InternationalizeActivityController extends Controller
{
    //
    public function index(Request $request){
    	$sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;

    	$internationalactivity= InternationalizeActivity::join('college_data',function($join){
    		$join->on('internationalize_activity.college','college_data.college');
    		$join->on('internationalize_activity.dept','college_data.dept');
    		})->orderBy($sortBy,$orderBy)->paginate(20);
        $internationalactivity->appends($request->except('page')); 
    	$user= Auth::user();
    	$data=compact('internationalactivity','user');

    	return view ('other/internationalize_activity',$data);
    }

    public function insert(Request $request){
        
        $this->validate($request,[
            'college'=>'required|max:11',
            'dept'=>'required|max:11',
            'activityName'=>'required|max:200',
            'place'=>'required|max:200',
            'host'=>'required|max:200',
            'guest'=>'required|max:200',
            'startDate'=>'required',
            'endDate'=>'required',
            ]);

        internationalactivity::create($request->all());

        return redirect('internationalize_activity')->with('success','新增成功');
    }

    public function search (Request $request){
    	$sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;

        $internationalactivity = InternationalizeActivity::join('college_data',function($join){
                $join->on('internationalize_activity.college','college_data.college');
                $join->on('internationalize_activity.dept','college_data.dept');
            });
        if($request->college != 0)
            $internationalactivity = $internationalactivity
                ->where('internationalize_activity.college',$request->college);
        if($request->dept != 0)
            $internationalactivity = $internationalactivity
                ->where('internationalize_activity.dept',$request->dept);        
        if($request->activityName != "")
            $internationalactivity = $internationalactivity
                ->where('activityName',"like","%$request->activityName%"); 
        if($request->place != "")
            $internationalactivity = $internationalactivity
                ->where('place',"like","%$request->place%"); 
        if($request->host != "")
            $internationalactivity = $internationalactivity
                ->where('host',"like","%$request->host%");
        if($request->guest != "")
            $internationalactivity = $internationalactivity
                ->where('guest',"like","%$request->guest%"); 
        if($request->startDate != "")
            $internationalactivity = $internationalactivity
                ->where('startDate','>',"$request->startDate");
        if($request->endDate != "")
            $internationalactivity = $internationalactivity
                ->where('endDate','<',"$request->endDate");


        $internationalactivity = $internationalactivity->orderBy($sortBy,$orderBy)
            ->paginate(20);
        $internationalactivity->appends($request->except('page'));    
        $user = Auth::user();
        $data = compact('internationalactivity','user');
        return view('other/internationalize_activity',$data);
    }
     public function delete($id){
        $internationalactivity = InternationalizeActivity::find($id);
        if(!Gate::allows('permission',$internationalactivity))
            return redirect('internationalize_activity');
        $internationalactivity->delete();
        return redirect('internationalize_activity');
        } 

    public function upload(Request $request){
        Excel::load($request->file('file'),function($reader){
            $array = $reader->toArray();
            $newArray = [];
            foreach ($array as $item) {
                foreach ($item as $key => $value) {

                    switch ($key) {
                        case '單位名稱':
                            $item['college'] = $value;
                            unset($item[$key]);
                            break;
                        case '系所部門':
                            $item['dept'] = $value;
                            unset($item[$key]);
                            break;
                        case '活動性質':
                            $item['activityName'] = $value;
                            unset($item[$key]);
                            break;
                        case '地點':
                            $item['place'] = $value;
                            unset($item[$key]);
                            break;
                        case '主辦':
                            $item['host'] = $value;
                            unset($item[$key]);
                            break;
                        case '外賓':
                            $item['guest'] = $value;
                            unset($item[$key]);
                            break;
                        case '開始時間':
                            $item['startDate'] = $value;
                            unset($item[$key]);
                            break;
                        case '結束時間':
                            $item['endDate'] = $value;
                            unset($item[$key]);
                            break;
                        default:
                            break;
                    }

                }                     
                $validator = Validator::make($item,[
                    'college' => 'required',
                ]);
                if($validator->fails()){
                    return redirect('internationalize_activity')
                        ->withErrors($validator,"upload");
                }
                array_push($newArray,$item);
            }
            InternationalizeActivity::insert($newArray);
        });
        return redirect('internationalize_activity');
    }

    public function example(Request $request){
        return response()->download(public_path().'/Excel_example/other/internationalize_activity.xlsx',"國際化活動.xlsx");
    }  
}
