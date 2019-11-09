<?php

namespace App\Http\Controllers;

use App\Email;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EmailController extends Controller
{
    public function get(){
        $emails=Email::get();
        return $emails;
    }

    public function postEmail(Request $r){
//        return $r;
        $this->validate($r,[
            'email' => 'required|max:255',
            'status' =>'required|max:20',
        ]);
        if($r->id){
            $email = Email::findOrFail($r->id);
        }
        else{
            $email = new Email();
            $email->createdBy = auth()->email()->id;
        }

        //$department->fkCompany = auth()->user()->fkCompany;

        $email->email = $r->email;
       // $email->fkDeptParent = $r->fkDeptParent;

//        if ($r->fkDeptParent==""){
//            $department->deptLevel = 0;
//        }else{
//
//            $depParent=Department::findOrFail($r->fkDeptParent);
//            $department->deptLevel = (($depParent->deptLevel)+1);
//        }
//        $department->deptHead = $r->deptHead;
        $email->status = $r->status;
        $email->save();
        return response()->json(["message" =>'Email Updated']);
    }
}
