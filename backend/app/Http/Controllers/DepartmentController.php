<?php

namespace App\Http\Controllers;

use App\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DepartmentController extends Controller
{
    public function get(){
        $departments=Department::get();
        //$departments=Department::select('id','departmentName','deptHead','fkDeptParent','deptLevel','status')->where('status', 'active')->orderBy('departmentName','ASC')->get();
        return $departments;
    }
    public function getAllMultipleRosterDepartment(){

        $departments=Department::where('rosterType',2)->get();

        return $departments;
    }
    public function getAllLevels()
    {
        $departmentsLevels=Department::select('deptLevel')->orderBy('id','ASC')->distinct()->get();
        return $departmentsLevels;
    }


    public function postDepartment(Request $r){
//        return $r;
        $this->validate($r,[
            'departmentName' => 'required|max:255',
            'deptLevel' => 'nullable|max:6',
            'status' =>'required|max:20',
            'rosterType' =>'required|max:2',
        ]);
        if($r->id){
            $department = Department::findOrFail($r->id);
        }
        else{
            $department = new Department();
            $department->createdBy = auth()->user()->id;
        }

        $department->fkCompany = auth()->user()->fkCompany;

        $department->departmentName = $r->departmentName;
        $department->fkDeptParent = $r->fkDeptParent;

        if ($r->fkDeptParent==""){
            $department->deptLevel = 0;
        }else{

            $depParent=Department::findOrFail($r->fkDeptParent);
            $department->deptLevel = (($depParent->deptLevel)+1);
        }
        $department->deptHead = $r->deptHead;
        $department->status = $r->status;
        $department->rosterType = $r->rosterType;
        $department->save();
        return response()->json(["message" =>'Department Updated']);
    }
}
