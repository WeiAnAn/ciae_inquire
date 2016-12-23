<?php

namespace App\Http\Controllers\other;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\internationalJournaleditor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Excel;
use Validator;
use App\CollegeData;


class InternationalJournalEditorController extends Controller
{
    public function index(Request $request){
        $sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;

        $internationaljeditor=internationalJournaleditor::
            join('college_data',function($join){
            $join->on('international_journal_editor.college','college_data.college');
            $join->on('international_journal_editor.dept','college_data.dept');
        })->orderBy($sortBy,$orderBy)
            ->paginate(20);
        $user = Auth::user();
        $data=compact('internationaljeditor','user');

    	return view ('other/international_journal_editor',$data);
    }
    public function insert(Request $request){

        $rules=[
            'college'=>'required|max:11',
            'dept'=>'required|max:11',
            'name'=>'required|max:20',
            'journalName'=>'required|max:200',
            'startDate'=>'required',
            'endDate'=>'required',
            'comments'=>'max:500',
        ];

        $message=[
            'required'=>'必須填寫:attribute欄位',
            'max'=>':attribute欄位的輸入長度不能大於:max',
        ];

        $validator=Validator::make($request->all(),$rules,$message);

        if($request->startDate > $request->endDate){
            $validator->errors()->add('endDate','開始時間必須在結束時間前');
            return redirect('international_journal_editor')->withErrors($validator)->withInput();
        }

        if($validator->fails()){
            return redirect('international_journal_editor')->withErrors($validator)->withInput();
        }

        internationalJournaleditor::create($request->all());
        return redirect('international_journal_editor')->with('success','新增成功');
    }

    public function search (Request $request){
        $sortBy = 'id';
        $orderBy = "desc";
        if($request->sortBy != null)
            $sortBy = $request->sortBy;
        if($request->orderBy != null)
            $orderBy = $request->orderBy;

        $internationaljeditor = internationalJournaleditor::join('college_data',function($join){
                $join->on('international_journal_editor.college','college_data.college');
                $join->on('international_journal_editor.dept','college_data.dept');
            });
        if($request->college != 0)
            $internationaljeditor = $internationaljeditor
                ->where('international_journal_editor.college',$request->college);
        if($request->dept != 0)
            $internationaljeditor = $internationaljeditor
                ->where('international_journal_editor.dept',$request->dept);
        if($request->name != "")
            $internationaljeditor = $internationaljeditor
                ->where('name',"like","%$request->name%"); 
        if($request->journalName != "")
            $internationaljeditor = $internationaljeditor
                ->where('journalName',"like","%$request->journalName%"); 
        if($request->startDate != "")
            $internationaljeditor = $internationaljeditor
                ->where('startDate','>=',"$request->startDate");
        if($request->endDate != "")
            $internationaljeditor = $internationaljeditor
                ->where('endDate','<=',"$request->endDate");
        if($request->comments != "")
            $internationaljeditor = $internationaljeditor
                ->where('comments',"like","%$request->comments%");

        $internationaljeditor = $internationaljeditor->orderBy($sortBy,$orderBy)
            ->paginate(20);
        $internationaljeditor->appends($request->except('page'));    
        $user = Auth::user();
        $data = compact('internationaljeditor','user');
        return view('other/international_journal_editor',$data);
    }

     public function edit($id){
        $internationaljeditor = internationaljournaleditor::find($id);
        if(Gate::allows('permission',$internationaljeditor))
            return view('other/international_journal_editor_edit',$internationaljeditor);
        return redirect('international_journal_editor');
    }

    public function update($id,Request $request){
        $internationaljeditor = internationaljournaleditor::find($id);
        if(!Gate::allows('permission',$internationaljeditor))
            return redirect('international_journal_editor');
        $rules=[
            'college'=>'required|max:11',
            'dept'=>'required|max:11',
            'name'=>'required|max:20',
            'journalName'=>'required|max:200',
            'startDate'=>'required',
            'endDate'=>'required',
            'comments'=>'max:500',
        ];

        $message=[
            'required'=>'必須填寫:attribute欄位',
            'max'=>':attribute欄位的輸入長度不能大於:max',
        ];

        $validator=Validator::make($request->all(),$rules,$message);

        if($request->startDate > $request->endDate){
            $validator->errors()->add('endDate','開始時間必須在結束時間前');
            return redirect("international_journal_editor/$id")->withErrors($validator)->withInput();
        }

        if($validator->fails()){
            return redirect("international_journal_editor/$id")->withErrors($validator)->withInput();
        }

        $internationaljeditor->update($request->all());
        return redirect('international_journal_editor')->with('success','更新成功');


    }



    public function delete($id){
        $IJE = internationalJournaleditor::find($id);
        if(!Gate::allows('permission',$IJE))
            return redirect('international_journal_editor');
        $IJE->delete();
        return redirect('international_journal_editor');
        }


    public function upload(Request $request){
        Excel::load($request->file('file'),function($reader){
            $array = $reader->toArray();
            $newArray = [];
            foreach ($array as $item) {
                foreach ($item as $key => $value) {

                    switch ($key) {
                        case '所屬一級單位':
                            $item['college'] = $value;
                            unset($item[$key]);
                            break;
                        case '所屬系所部門':
                            $item['dept'] = $value;
                            unset($item[$key]);
                            break;
                        case '期刊名稱':
                            $item['journalName'] = $value;
                            unset($item[$key]);
                            break;
                        case '期刊編輯者':
                            $item['name'] = $value;
                            unset($item[$key]);
                            break;
                        case '開始擔任時間':
                            $item['startDate'] = $value;
                            unset($item[$key]);
                            break;
                        case '結束擔任時間':
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
                            return redirect('international_journal_editor')
                                ->withErrors($validator,"upload");
                            break;
                    }
                }
                $validator = Validator::make($item,[
                    'college'=>'required|max:11',
                    'dept'=>'required|max:11',
                    'name'=>'required|max:20',
                    'journalName'=>'required|max:200',
                    'startDate'=>'required',
                    'endDate'=>'required',
                    'comments'=>'max:500',
                ]);
                if($validator->fails()){
                    return redirect('international_journal_editor')
                        ->withErrors($validator,"upload");
                }
                if(CollegeData::where('college',$item['college'])
                        ->where('dept',$item['dept'])->first()==null){
                    $validator->errors()->add('number','系所代碼錯誤');
                    return redirect('international_journal_editor')
                                ->withErrors($validator,"upload");
                }
                if(!Gate::allows('permission',(object)$item)){
                    $validator->errors()->add('permission','無法新增未有權限之系所部門');
                    return redirect('international_journal_editor')
                                ->withErrors($validator,"upload");
                }
                array_push($newArray,$item);
            }
            internationaljournaleditor::insert($newArray);
        });
        return redirect('international_journal_editor');
    }

    public function example(Request $request){
        return response()->download(public_path().'/Excel_example/other/international_journal_editor.xlsx',"擔任國際期刊編輯.xlsx");
    }  
}
