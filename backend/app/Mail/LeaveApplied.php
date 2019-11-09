<?php

namespace App\Mail;


use App\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Auth;
use DB;

class LeaveApplied extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    protected $emp;

    public function __construct($emp)
    {
        $this->emp = $emp;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $emp=Employee::select('employeeinfo.fkDepartmentId','employeeinfo.id','employeeinfo.fkDesignation',
            'designations.title as designationTitle','departments.departmentName','attemployeemap.attDeviceUserId',
            DB::raw("CONCAT(COALESCE(firstName,''),' ',COALESCE(middleName,''),' ',COALESCE(lastName,'')) AS empFullname"))
            ->leftJoin('departments','departments.id','employeeinfo.fkDepartmentId')
            ->leftJoin('designations','designations.id','employeeinfo.fkDesignation')
            ->leftJoin('attemployeemap','attemployeemap.employeeId','employeeinfo.id')
            ->where('fkUserId',Auth::user()->id)->first();



        $emp=Employee::where('fkUserId',auth()->user()->id)->first();
        $leaves=Leave::select('leaves.*','leavecategories.categoryName')
            ->where('fkEmployeeId',$emp->id)

            ->orderBy('leaves.id','desc')
            ->get();

        return $leaves;

        //print_r($emp); exit;

        //return $this->subject('Leave Applied in HRMTIS notification!')->view('Email.-')->with(['emp' => $emp]);

        return $this->subject('Leave Applied in HRMTIS notification!')->view('Email.LeaveApplied')->with(['emp' => $this->emp]);

    }
}
