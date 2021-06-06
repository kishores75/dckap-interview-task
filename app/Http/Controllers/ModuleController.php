<?php

namespace App\Http\Controllers;

use App\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModuleController extends Controller
{
    public function moduleAdd(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:modules,name',
            'module' => 'required'
        ]);
        //Parent default value is 0.  Which means helps to create module.
        $save = Module::create([
            'name' => $request->name,
            'parent_id' => $request->module,
        ]);
        if($save) return response()->json(['success' => 'Data has been added succesfully']);
        else return response()->json(['failure' => 'Something went wrong try again later']);
    }
}
