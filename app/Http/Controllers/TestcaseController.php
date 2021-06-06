<?php

namespace App\Http\Controllers;

use App\Testcase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class TestcaseController extends Controller
{
    public function testCaseList()
    {
        $data['modules'] = DB::table('modules')->select('id','name','parent_id')->where('parent_id', 0)->get();
        $data['submodules'] = DB::table('modules')->select('id','name','parent_id')->get();
        return view('testcaselist', $data);
    }
    public function testcaseAdd(Request $request)
    {
        $this->validate($request, [
            'module_id' => 'required',
            'summary' => 'required'
        ],
        [
            'module_id.required' => 'The module field is required',
        ]);
        $filePath = '';
        $fileName = $request->file('file');
        if($fileName){
            $filePath = strtolower(date('Y-m-d').'_'.microtime(true).'_'.$fileName->getClientOriginalName());
            $fileName->move(public_path('uploads'),$filePath);
        }
        $save = Testcase::create([
            'module_id' => $request->module_id,
            'summary' => $request->summary,
            'description' => $request->description,
            'file' => $filePath,
        ]);
        if($save) return response()->json(['success' => 'Data has been added succesfully']);
        else {
            File::delete(public_path('uploads/'.$filePath));
            return response()->json(['failure' => 'Something went wrong try again later']);
        }

    }
    public function getTestcases(Request $request){
        ## Read value
    	$dropdown = $request->get("custom");
        if(empty($dropdown)) $dropdown = 'all';
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page
        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');
        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value
        if($dropdown == "all"){
            // Total records
            $totalRecords = DB::table('testcases')->select('count(*) as allcount')->count();
            $totalRecordswithFilter = DB::table('testcases')->select('count(*) as allcount')->where('summary', 'like', '%' .$searchValue . '%')->count();
            // Fetch records
            $records = DB::table('testcases')->orderBy($columnName,$columnSortOrder)->where('summary', 'like', '%' .$searchValue . '%')->skip($start)->take($rowperpage)->get();
        }else{
            // Total records
            $totalRecords = DB::table('testcases')->select('count(*) as allcount')->count();
            $totalRecordswithFilter = DB::table('testcases')->select('count(*) as allcount')->where('summary', 'like', '%' .$searchValue . '%')->count();
            // Fetch records
            $records = DB::select("SELECT * from testcases  WHERE  module_id = '$dropdown' AND( summary like '%$searchValue%' ) order by $columnName $columnSortOrder limit $rowperpage offset $start");
        }
        $data_arr = array();
        foreach($records as $record){
            $id = $record->id;
            $summary = $record->summary;
            $description = $record->description;
            ($record->file) ? $file = '<a href="uploads/'.$record->file.'" target="_blank" class="btn btn-success waves-effect waves-light">View File</a>' : $file = 'No-file';
            // ($record->file) ? $file = '<a href="{{asset(uploads/'.$record->file.')}}" class="btn btn-success waves-effect waves-light">View File</a>' : $file = 'No-file';
            $delete = '<button class="btn btn-danger waves-effect waves-light deletetestcase" type="button" data-id="'.$id.'">Delete</button>';
            $data_arr[] = array(
                "id" => $id,
                "summary" => $summary,
                "description" => $description,
                "file" => $file,
                "action_delete" => $delete
            );
        }
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );
        echo json_encode($response);
        exit;
    }
    public function testcaseDelete(Request $request)
    {
        $id = $request->input('id');
        $data = DB::table('testcases')->where('id', $id)->first();
        if($data){
            $filePath = $data->file;
            if(File::exists(public_path('uploads/'.$filePath))){
                File::delete(public_path('uploads/'.$filePath));
            }
            $result = DB::table('testcases')->where('id', $id)->delete();
            if ($result) return response()->json(['success' => 'Your Data has been deleted']);
            else return response()->json(['success' => 'Something went wrong try again later']);
        }
        else {
            return response()->json(['success' => 'Something went wrong try again later']);
        }
    }
}
