<?php

namespace App\Http\Controllers;

use App\Employee;
use App\Leave;
use App\LeaveCategory;
use App\Mail\LeaveApplied;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;
use Auth;
use DB;
use Illuminate\Support\Facades\Mail;

class LeaveController extends Controller
{
    public function getLeaveCategory(){
        $category=LeaveCategory::select('id','categoryName')->get();
        return $category;
    }

    public function assignLeavePersonal(Request $r){
//       return $r;
//       return auth()->user()->id;

       //  $emp=Employee::where('fkUserId',auth()->user()->id)->first();

        $emp=Employee::select('employeeinfo.fkDepartmentId','employeeinfo.id','employeeinfo.fkDesignation',
            'designations.title as designationTitle','departments.departmentName','attemployeemap.attDeviceUserId',
            DB::raw("CONCAT(COALESCE(firstName,''),' ',COALESCE(middleName,''),' ',COALESCE(lastName,'')) AS empFullname"))
            ->leftJoin('departments','departments.id','employeeinfo.fkDepartmentId')
            ->leftJoin('designations','designations.id','employeeinfo.fkDesignation')
            ->leftJoin('attemployeemap','attemployeemap.employeeId','employeeinfo.id')
            ->where('fkUserId',Auth::user()->id)->first();


         $otherReceipant=Employee::
            leftJoin('designations',function ($query) use($emp){

                $query->on('designations.id','=','employeeinfo.fkDesignation')
                ->where('fkDepartmentId',$emp['fkDepartmentId']);


            })->leftJoin('departments',function ($query) use($emp){

                $query->on('departments.id','=','employeeinfo.fkDepartmentId');
            })
            ->where(function ($query) {
                $query->where('designations.title', '=', Leave_Accept_Access['Manager'])
                    ->orWhere('departments.departmentName', '=', Leave_Accept_Access['Hr']);
            })
            ->get();

        $leave=new Leave();
        $leave->fkEmployeeId=$emp->id;
        $leave->applicationDate=date('Y-m-d');
        $leave->fkLeaveCategory=$r->fkLeaveCategory;
//           Pending, Approved, Rejected
        $leave->applicationStatus="Pending";

        $leave->endDate= Carbon::parse($r->endDate)->format('Y-m-d');
        $leave->startDate=Carbon::parse($r->startDate)->format('Y-m-d');
        $leave->noOfDays=$r->noOfDays;
        $leave->remarks=$r->remark;
        $leave->createdBy=auth()->user()->id;

        $leave->save();

        if ($emp['email']!='' && $emp['email']!= null){

            Mail::to($emp['email'])->send(new LeaveApplied());
            Mail::to('faruk.totaltvs@gmail.com')->send(new LeaveApplied($emp));
        }

        foreach ($otherReceipant as $oR){

            if ($oR['email']!='' && $oR['email']!= null){

                Mail::to($oR['email'])->send(new LeaveApplied($emp));

            }
        }
    }

    public function getMyLeave(Request $r){

        $emp=Employee::where('fkUserId',auth()->user()->id)->first();
        $leaves=Leave::select('leaves.*','leavecategories.categoryName')
            ->where('fkEmployeeId',$emp->id)
//            ->whereIn('leaves.fkLeaveCategory',[1,2,3,4])
            ->leftJoin('leavecategories','leavecategories.id','leaves.fkLeaveCategory')
            ->orderBy('leaves.id','desc')
            ->get();

        return $leaves;
    }

    public function assignLeave(Request $r){

        foreach ($r->allEmp as $empid){

            $leave=new Leave();
            $leave->fkEmployeeId=$empid;
            $leave->applicationDate=date('Y-m-d');
            $leave->fkLeaveCategory=$r->fkLeaveCategory;
//           Pending, Approved, Rejected
            $leave->applicationStatus="Approved";

            $leave->departmentHeadApproval=auth()->user()->id;
            $leave->HR_adminApproval=auth()->user()->id;

            $leave->endDate= Carbon::parse($r->endDate)->format('Y-m-d');
            $leave->startDate=Carbon::parse($r->startDate)->format('Y-m-d');
            $leave->noOfDays=$r->noOfDays;
            $leave->remarks=$r->remarks;
            $leave->createdBy=auth()->user()->id;
            $leave->save();


          //  $emp=Employee::where('fkUserId',auth()->user()->id)->first();

            $emp=Employee::select('employeeinfo.fkDepartmentId','employeeinfo.id','employeeinfo.fkDesignation',
                'designations.title as designationTitle','departments.departmentName','attemployeemap.attDeviceUserId',
                DB::raw("CONCAT(COALESCE(firstName,''),' ',COALESCE(middleName,''),' ',COALESCE(lastName,'')) AS empFullname"))
                ->leftJoin('departments','departments.id','employeeinfo.fkDepartmentId')
                ->leftJoin('designations','designations.id','employeeinfo.fkDesignation')
                ->leftJoin('attemployeemap','attemployeemap.employeeId','employeeinfo.id')
                ->where('employeeinfo.id',$empid)->first();

            $otherReceipant=Employee::leftJoin('designations',function ($query) use($emp){

                $query->on('designations.id','=','employeeinfo.fkDesignation')
                    ->where('fkDepartmentId',$emp['fkDepartmentId']);


            })->leftJoin('departments',function ($query) use($emp){

                $query->on('departments.id','=','employeeinfo.fkDepartmentId');



            })
                ->where(function ($query) {
                    $query->where('designations.title', '=', Leave_Accept_Access['Manager'])
                        ->orWhere('departments.departmentName', '=', Leave_Accept_Access['Hr']);
                })



                ->get();

            if ($emp['email']!='' && $emp['email']!= null){

                Mail::to($emp['email'])->send(new LeaveApplied());
                Mail::to('faruk.totaltvs@gmail.com')->send(new LeaveApplied($emp));
            }

            foreach ($otherReceipant as $oR){

                if ($oR['email']!='' && $oR['email']!= null){

                    Mail::to($oR['email'])->send(new LeaveApplied($emp));

                }
            }

        }
        return $r;
    }

    public function getLeaveSummeryDetails(Request $r){

        $leaves=Leave::select('leaves.*','leavecategories.categoryName')
            ->where('fkEmployeeId',$r->id)
            ->leftJoin('leavecategories','leavecategories.id','leaves.fkLeaveCategory')
            ->where(function ($query) {
                $query->where('leaves.departmentHeadApproval', '!=', 0)
                    ->Where('leaves.departmentHeadApproval', '!=', null);
            })->where(function ($query) {
                $query->where('leaves.HR_adminApproval', '!=', 0)
                    ->Where('leaves.HR_adminApproval', '!=', null);
            })
            ->orderBy('leaves.id','desc')
            ->get();

        return $leaves;
    }
    public function getLeaveSummery(Request $r){
        $leaves=Leave::select('employeeinfo.id','employeeinfo.firstName','employeeinfo.middleName',
            'employeeinfo.lastName',
            DB::raw('sum(case when fkLeaveCategory = 1 then noOfDays else 0 end) as cs'),
            DB::raw('sum(case when fkLeaveCategory = 2 then noOfDays else 0 end) as sick'),
            DB::raw('sum(case when fkLeaveCategory = 4 then noOfDays else 0 end) as lwp'),
            DB::raw('sum(case when fkLeaveCategory = 3 then noOfDays else 0 end) as marri'),
            DB::raw('sum(case when fkLeaveCategory = 6 then noOfDays else 0 end) as earn'),
            DB::raw('sum(case when fkLeaveCategory = 7 then noOfDays else 0 end) as Maternity'),
            DB::raw('sum(case when fkLeaveCategory = 8 then noOfDays else 0 end) as Paternity'),
            DB::raw('sum(case when fkLeaveCategory = 9 then noOfDays else 0 end) as anual'))
            ->leftJoin('employeeinfo','employeeinfo.id','leaves.fkEmployeeId')
            ->where(function ($query) {
                $query->where('leaves.departmentHeadApproval', '!=', 0)
                    ->Where('leaves.departmentHeadApproval', '!=', null);
            })->where(function ($query) {
                $query->where('leaves.HR_adminApproval', '!=', 0)
                    ->Where('leaves.HR_adminApproval', '!=', null);
            })
//            ->where('leaves.applicationStatus','Approved')
//            ->whereIn('leaves.fkLeaveCategory',[1,2,5,4])
            ->groupBy('leaves.fkEmployeeId');
        if($r->startDate && $r->endDate){

            $leaves = $leaves->whereRaw("date(leaves.startDate) between '".$r->startDate."' and '".$r->endDate."'  ");
        }
        if ($r->userId){
            $leaves=$leaves->where('employeeinfo.fkUserId',$r->userId);
        }
        $leaves=$leaves->get();

        $datatables = Datatables::of($leaves);
        return $datatables->make(true);

    }

    public function getLeaveRequests(){

        $emp=Employee::select('fkDesignation','id')->where('fkUserId',Auth::user()->id)->first();

        $leaves=Leave::select('designations.title as userDesignationTitle','leaves.*','employeeinfo.firstName',
            'employeeinfo.middleName','employeeinfo.lastName','leavecategories.categoryName')
            ->leftJoin('employeeinfo','employeeinfo.id','leaves.fkEmployeeId')

            ->leftJoin('designations',function($join) use($emp) {
                $join->where('designations.id', '=', $emp['fkDesignation']);

            })
            ->leftJoin('leavecategories','leavecategories.id','leaves.fkLeaveCategory');

        $datatables = Datatables::of($leaves);
        return $datatables->make(true);

    }

    public function changeStatus(Request $r){

        $msg='';

        $emp=Employee::select('employeeinfo.fkDepartmentId','employeeinfo.id','employeeinfo.fkDesignation',
            'designations.title as designationTitle','departments.departmentName')
            ->leftJoin('departments','departments.id','employeeinfo.fkDepartmentId')
            ->leftJoin('designations','designations.id','employeeinfo.fkDesignation')
            ->where('fkUserId',Auth::user()->id)->first();

        $getLeaveInfo=Leave::findOrFail($r->id);

        if ($emp['designationTitle']==Leave_Accept_Access['Manager']){

            if ($getLeaveInfo->departmentHeadApproval!=null)
            {
                $msg='You Already Approved This Req';
            }else{
                $getLeaveInfo->departmentHeadApproval=$emp['id'];
                $getLeaveInfo->save();
                $msg='Request Accepted';
            }

        }elseif ($emp['designationTitle']==Leave_Accept_Access['Hr']){

            if ($getLeaveInfo->HR_adminApproval == null || $getLeaveInfo->HR_adminApproval == '0'){

                if ($getLeaveInfo->departmentHeadApproval!=null || $getLeaveInfo->departmentHeadApproval!='0')
                {
                    $getLeaveInfo->HR_adminApproval=$emp['id'];
                    $getLeaveInfo->save();
                    $msg='Request Accepted';


                }else{
                    $msg='Department Head did not Approved this req Yet';
                }

            }else{
                $msg='You Already Approved This Req';
            }

        }




        return response()->json($msg);




//        Leave::where('id',$r->id)->update(['applicationStatus'=>$r->applicationStatus,'departmentHeadApproval'=>$emp['id']]);
//        return $r;
    }

    public function getIndividual(Request $r){

        return Leave::select('leaves.*','employeeinfo.firstName','employeeinfo.middleName','employeeinfo.lastName')
            ->leftJoin('employeeinfo','employeeinfo.id','leaves.fkEmployeeId')
            ->findOrFail($r->id);
    }

    public function updateIndividual(Request $r){

        $msg='';

        $leave=Leave::findOrFail($r->id);

        $leave->applicationDate=date('Y-m-d');
        $leave->fkLeaveCategory=$r->fkLeaveCategory;
        $leave->endDate= Carbon::parse($r->endDate)->format('Y-m-d');
        $leave->startDate=Carbon::parse($r->startDate)->format('Y-m-d');
        $leave->noOfDays=$r->noOfDays;
        $leave->remarks=$r->remark;


        if($r->status){
            $leave->applicationStatus=$r->status;
        }
        if($r->rejectCause){

            $emp=Employee::select('employeeinfo.fkDepartmentId','employeeinfo.id','employeeinfo.fkDesignation',
                'designations.title as designationTitle','departments.departmentName')
                ->leftJoin('departments','departments.id','employeeinfo.fkDepartmentId')
                ->leftJoin('designations','designations.id','employeeinfo.fkDesignation')
                ->where('fkUserId',Auth::user()->id)->first();

            if ($emp['designationTitle']==Swap_Accept_Access['Manager']) {

                if ($leave->departmentHeadApproval == '0') {
                    $msg = 'You Already Rejected This Req';
                } else {
                    $leave->departmentHeadApproval = 0;
                    $leave->save();
                    $msg = 'Request Rejected';
                }

            }elseif ($emp['designationTitle']==Swap_Accept_Access['Hr']){

                if ($leave->HR_adminApproval == null || $leave->departmentHeadApproval != 0){

                    if ($leave->departmentHeadApproval!=null)
                    {
                        $leave->HR_adminApproval=0;
                        $leave->save();
                        $msg='Request Rejrcted';
                    }else{
                        $msg='Department Head did not Approved this req Yet';
                    }

                }elseif($leave->HR_adminApproval=='0'){
                    $msg='You Already Rejected This Req';
                }

            }

            $leave->rejectCause=$r->rejectCause;
        }

        $leave->save();

        return response()->json($msg);

    }
}
