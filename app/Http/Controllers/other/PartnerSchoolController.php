<?php

namespace App\Http\Controllers\other;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PartnerSchool;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Excel;
use Validator;
use App\CollegeData;


class PartnerSchoolController extends Controller
{
    //
    public function index (Request $request){
    	$sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;

    	$partner= PartnerSchool::join('college_data',function($join){
    		$join->on('partner_school.college','college_data.college');
    		$join->on('partner_school.dept','college_data.dept');
    		})->orderBy($sortBy,$orderBy)->paginate(20);
        $partner->appends($request->except('page')); 
    	$user=Auth::user();
    	$data=compact('partner','user');

    	return view ('other/partner_school',$data);
    }

    public function insert(Request $request){
        
        $this->validate($request,[
            'college'=>'required|max:11',
            'dept'=>'required|max:11',
            'nation'=>'required|max:20',
            'chtName'=>'required|max:50',
            'engName'=>'required|max:80',
            'startDate'=>'required',
            'endDate'=>'required',
            'comments'=>'max:500',
            ]);

        partnerschool::create($request->all());

        return redirect('partner_school')->with('success','新增成功');
    }

    public function search (Request $request){
    	$sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;

        $partner = PartnerSchool::join('college_data',function($join){
                $join->on('partner_school.college','college_data.college');
                $join->on('partner_school.dept','college_data.dept');
            });
        if($request->college != 0)
            $partner = $partner
                ->where('partner_school.college',$request->college);
        if($request->dept != 0)
            $partner = $partner
                ->where('partner_school.dept',$request->dept);        
        if($request->nation != "")
            $partner = $partner
                ->where('nation',"like","%$request->nation%"); 
        if($request->chtName != "")
            $partner = $partner
                ->where('chtName',"like","%$request->chtName%"); 
        if($request->engName != "")
            $partner = $partner
                ->where('engName',"like","%$request->engName%");
        if($request->startDate != "")
            $internationalactivity = $internationalactivity
                ->where('startDate','>',"$request->startDate");
        if($request->endDate != "")
            $internationalactivity = $internationalactivity
                ->where('endDate','<',"$request->endDate");
        if($request->comments != "")
            $partner = $partner
                ->where('comments','like',"%$request->comments%");


        $partner = $partner->orderBy($sortBy,$orderBy)
            ->paginate(20);
        $partner->appends($request->except('page'));    
        $user = Auth::user();
        $data = compact('partner','user');
        return view('other/partner_school',$data);
    }
      public function delete($id){
        $partner = PartnerSchool::find($id);
        if(!Gate::allows('permission',$partner))
            return redirect('partner_school');
        $partner->delete();
        return redirect('partner_school');
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
                        case '國家':
                            $item['nation'] = $value;
                            unset($item[$key]);
                            break;
                        case '中文校名':
                            $item['chtName'] = $value;
                            unset($item[$key]);
                            break;
                        case '英文校名':
                            $item['engName'] = $value;
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
                        case '備註':
                            $item['comments'] = $value;
                            unset($item[$key]);
                            break;                        
                        default:
                            $validator = Validator::make($item,[]);
                            $validator->errors()->add('format','檔案欄位錯誤');
                            return redirect('partner_school')
                                ->withErrors($validator,"upload");
                            break;
                    }
                }
                $validator = Validator::make($item,[
                    'college'=>'required|max:11',
                    'dept'=>'required|max:11',
                    'nation'=>'required|max:20',
                    'chtName'=>'required|max:50',
                    'engName'=>'required|max:80',
                    'startDate'=>'required',
                    'endDate'=>'required',
                    'comments'=>'max:500',
                ]);
                if($validator->fails()){
                    return redirect('partner_school')
                        ->withErrors($validator,"upload");
                }
                if(CollegeData::where('college',$item['college'])
                        ->where('dept',$item['dept'])->first()==null){
                    $validator->errors()->add('number','系所代碼錯誤');
                    return redirect('partner_school')
                                ->withErrors($validator,"upload");
                }
                if(!Gate::allows('permission',(object)$item)){
                    $validator->errors()->add('permission','無法新增未有權限之系所部門');
                    return redirect('partner_school')
                                ->withErrors($validator,"upload");
                }
                array_push($newArray,$item);
            }
            PartnerSchool::insert($newArray);
        });
        return redirect('partner_school');
    }

    public function example(Request $request){
        return response()->download(public_path().'/Excel_example/other/partner_school.xlsx',"姊妹校締約情形.xlsx");
    }
     
}
