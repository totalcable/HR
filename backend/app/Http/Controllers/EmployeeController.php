<?php

namespace App\Http\Controllers;

use App\AttEmployeeMap;
use App\Employee;
use App\ShiftLog;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;
use Route;
use Auth;
use DB;
use PDF;



class EmployeeController extends Controller {

    public function __construct() {
//        $this->middleware('auth:api');
    }

    public function getTotalActiveEmp(){

        return $employee = Employee::leftjoin('attemployeemap', 'attemployeemap.employeeId', '=', 'employeeinfo.id')
            ->where('resignDate', null)
            ->whereNotNull('attemployeemap.attDeviceUserId')
            ->count('employeeinfo.id');
    }

    public function getTotalInActiveEmp(){

        return $employee = Employee::
            whereNotNull('employeeinfo.resignDate')
            ->count('employeeinfo.id');
    }

    public function updateJoinInfo(Request $r) {

      //  return $r;
        //$aa =Route::getCurrentRoute()->getActionName();
        //return $r->route()->getAction()['prefix']; // return 'api'
       // $log = new Log();
//        $aa = $r->route()->getActionMethod();
//        preg_match('/([a-z]*)@/i', $r->route()->getActionName(), $matches);
//
//        $controllerName = $matches[1];
//
//        $localIp = gethostbyname(gethostname());
//
//        $date_time = date("Y-m-d H:i:s");
//
//        //return Auth::user();
 //     return  $u_id = auth()->user()->id;
//        return $controllerName . ' ' . $aa . ' ' . $localIp . ' ' . $date_time . ' ' . $u_id;
        //return $aa; 

        $this->validate($r, [
            'accessPin' => 'nullable|max:11',
            'attDeviceUserId' => 'max:11',
            'supervisor' => 'max:255',
        ]);

        $days = array();
        for ($i = 0; $i < count($r->weekend); $i++) {
            array_push($days, $r->weekend[$i]['item_id']);

        }
        $tags = implode(',', $days);

        $joinInfo = Employee::findOrFail($r->id);
        if ($r->actualJoinDate == null) {
            $joinInfo->actualJoinDate = null;
        } else {
            $joinInfo->actualJoinDate = Carbon::parse($r->actualJoinDate)->format('Y-m-d');
        }

        if ($r->resignDate == null) {
            $joinInfo->resignDate = null;
        } else {
            $joinInfo->resignDate = Carbon::parse($r->resignDate)->format('Y-m-d');
        }

        $joinInfo->weekend = $tags;
        $joinInfo->accessPin = $r->accessPin;
        $joinInfo->inDeviceNo = $r->inDeviceNo;
        $joinInfo->outDeviceNo = $r->outDeviceNo;

        $joinInfo->employeeId = $r->employeeId;
        $joinInfo->fkDepartmentId = $r->department;
        $joinInfo->fkDesignation = $r->designation;
        $joinInfo->fkEmployeeType = $r->empType;
        $joinInfo->bloodGroup = $r->bloodGroup;
        $joinInfo->email_off = $r->email_off;
        $joinInfo->workingLocation = $r->workingLocation;
        $joinInfo->contactNo = $r->contactNo;
        $joinInfo->salary = $r->salary;

        $joinInfo->e_name = $r->e_name;
        $joinInfo->e_street_address = $r->e_street_address;
        $joinInfo->e_apartment_unit = $r->e_apartment_unit;
        $joinInfo->e_city = $r->e_city;
        $joinInfo->e_state = $r->e_state;
        $joinInfo->e_zip_code = $r->e_zip_code;
        $joinInfo->e_phone = $r->e_phone;
        $joinInfo->e_alternate_phone = $r->e_alternate_phone;
        $joinInfo->e_relationship = $r->e_relationship;


        $joinInfo->supervisor = $r->supervisor;
        $joinInfo->probationPeriod = $r->probationPeriod;
        $joinInfo->fkActivationStatus = $r->fkActivationStatus;

        if ($r->attDeviceUserId != null) {
            if (AttEmployeeMap::where('employeeId', $r->id)) {
                AttEmployeeMap::where('employeeId', $r->id)->update(['attDeviceUserId' => $r->attDeviceUserId]);
            }

            AttEmployeeMap::firstOrCreate([
                'attDeviceUserId' => $r->attDeviceUserId,
                'employeeId' => $r->id,
            ]);
        }

        //Log table data insert start

       // $log->module_name = $controllerName;
       // $log->method_name = $aa;
       // $log->information = $localIp . ' ' . $date_time;
        //return $logInfo;
        //$log->save();

        //Log table data insert end

        $joinInfo->save();

        return response()->json(["message" => "Join Info updated"]);
    }

    public function getJoinInfo(Request $r) {
        $joinInfo = Employee::select('attemployeemap.attDeviceUserId','contactNo', 'salary','actualJoinDate', 'resignDate', 'weekend',
            'accessPin','employeeinfo.fkDepartmentId','employeeinfo.employeeId','fkEmployeeType','employeeinfo.fkDesignation',
            'workingLocation','supervisor', 'probationPeriod','bloodGroup','email_off', 'employeeinfo.fkActivationStatus', 'employeeinfo.inDeviceNo',
            'e_name','e_street_address','e_apartment_unit','e_city','e_state','e_zip_code','e_phone','e_alternate_phone','e_relationship',
            'employeeinfo.outDeviceNo')
                ->leftJoin('attemployeemap', 'attemployeemap.employeeId', 'employeeinfo.id')
                ->where('employeeinfo.id', '=', $r->id)
                ->first();

        return response()->json($joinInfo);
    }

    public function getBasicinfo(Request $r) {

        $basicinfo = Employee::select('employeeinfo.id','EmployeeId', 'photo','cv', 'firstName', 'middleName', 'lastName', 'fkEmployeeType', 'email', 'gender', 'birthdate',
             'streetAddress','apartmentUnit','city','state','zipCode','homePhone','maritalStatus','nationalId', 'fkDesignation', 'fkDepartmentId',
            'departmentName', 'title', 'alterContactNo')
                ->leftjoin('designations', 'designations.id', '=', 'employeeinfo.fkDesignation')
                ->leftjoin('departments', 'departments.id', '=', 'employeeinfo.fkDepartmentId')
                ->leftjoin('employeetypes', 'employeetypes.id', '=', 'employeeinfo.fkEmployeeType')
                ->where('employeeinfo.id', $r->empid)
                ->first();

        return $basicinfo;
    }

    public function getAllEmployee(Request $r) {
        $employee = Employee::select('attemployeemap.attDeviceUserId', 'employeeinfo.firstName', 'employeeinfo.lastName', 'employeeinfo.middleName', 'employeeinfo.EmployeeId', 'designations.title', 'departments.departmentName', 'employeeinfo.id as empid'
                        , 'employeeinfo.weekend',DB::raw("CONCAT(COALESCE(employeeinfo.firstName,''),' ',COALESCE(employeeinfo.middleName,''),' ',COALESCE(employeeinfo.lastName,'')) AS empFullname"))
                ->leftjoin('designations', 'designations.id', '=', 'employeeinfo.fkDesignation')
                ->leftjoin('departments', 'departments.id', '=', 'employeeinfo.fkDepartmentId')
                ->leftJoin('attemployeemap', 'attemployeemap.employeeId', 'employeeinfo.id');
//                ->where('resignDate', null);
//            ->where('employeeinfo.fkCompany' , auth()->user()->fkCompany);

        $datatables = Datatables::of($employee);
        return $datatables->make(true);
    }

    public function getAllEmployeeInfo() {
        return $employee = Employee::select('employeeinfo.firstName', 'employeeinfo.lastName', 'employeeinfo.middleName', 'employeeinfo.EmployeeId', 'designations.title', 'departments.departmentName', 'employeeinfo.id as empid', 'attemployeemap.attDeviceUserId')
                ->leftjoin('designations', 'designations.id', '=', 'employeeinfo.fkDesignation')
                ->leftjoin('departments', 'departments.id', '=', 'employeeinfo.fkDepartmentId')
                ->leftjoin('attemployeemap', 'attemployeemap.employeeId', '=', 'employeeinfo.id')
                ->where('resignDate', null)
                ->whereNotNull('attemployeemap.attDeviceUserId')
                ->get();
    }
    public function getAllSingleRosterDepartmentEployee() {

        return $employee = Employee::select('employeeinfo.firstName', 'employeeinfo.lastName', 'employeeinfo.middleName', 'employeeinfo.EmployeeId', 'designations.title', 'departments.departmentName', 'employeeinfo.id as empid', 'attemployeemap.attDeviceUserId')
                ->leftjoin('designations', 'designations.id', '=', 'employeeinfo.fkDesignation')
                ->leftjoin('departments', 'departments.id', '=', 'employeeinfo.fkDepartmentId')
                ->leftjoin('attemployeemap', 'attemployeemap.employeeId', '=', 'employeeinfo.id')
                ->where('resignDate', null)
                ->where('departments.rosterType',1)
                ->whereNotNull('attemployeemap.attDeviceUserId')
                ->get();
    }
//    public function getAllMultipleRosterDepartmentEployee() {
//
//        return $employee = Employee::select('employeeinfo.firstName', 'employeeinfo.lastName', 'employeeinfo.middleName', 'employeeinfo.EmployeeId', 'designations.title', 'departments.departmentName', 'employeeinfo.id as empid', 'attemployeemap.attDeviceUserId')
//                ->leftjoin('designations', 'designations.id', '=', 'employeeinfo.fkDesignation')
//                ->leftjoin('departments', 'departments.id', '=', 'employeeinfo.fkDepartmentId')
//                ->leftjoin('attemployeemap', 'attemployeemap.employeeId', '=', 'employeeinfo.id')
//                ->where('resignDate', null)
//                ->where('departments.rosterType',2)
//                ->whereNotNull('attemployeemap.attDeviceUserId')
//                ->get();
//    }

    public function getAllEmployeeInfoForDepartment(Request $r) {
        return $employee = Employee::select('employeeinfo.id as empId','employeeinfo.firstName', 'employeeinfo.lastName', 'employeeinfo.middleName', 'employeeinfo.EmployeeId',
            'designations.title', 'departments.departmentName', 'employeeinfo.id as empid', 'attemployeemap.attDeviceUserId',
            DB::raw("CONCAT(COALESCE(firstName,''),' ',COALESCE(middleName,''),' ',COALESCE(lastName,'')) AS empFullname"))

                ->leftjoin('designations', 'designations.id', '=', 'employeeinfo.fkDesignation')
                ->leftjoin('departments', 'departments.id', '=', 'employeeinfo.fkDepartmentId')
                ->leftjoin('attemployeemap', 'attemployeemap.employeeId', '=', 'employeeinfo.id')
                ->where('resignDate', null)
                ->whereNotNull('attemployeemap.attDeviceUserId')
                ->whereIn('departments.id', $r->departments)
                ->get();
    }

    public function getAllEmployeeForAttendance(Request $r) {

        $employee = Employee::select('shiftlog.startDate', 'shiftlog.weekend', 'shift.shiftName', 'employeeinfo.firstName', 'employeeinfo.middleName', 'employeeinfo.lastName', 'employeeinfo.EmployeeId', 'employeeinfo.id as empid')
                ->leftjoin('shiftlog', 'shiftlog.fkemployeeId', '=', 'employeeinfo.id')
                ->leftjoin('shift', 'shift.shiftId', '=', 'shiftlog.fkshiftId')
                ->where('employeeinfo.fkActivationStatus', 1)
                ->where('shiftlog.endDate', null);


        $datatables = Datatables::of($employee);
        return $datatables->make(true);
    }
    public function cvDelete(Request $r){

       // return $r->empid;

        $employeeInfo = Employee::findOrFail($r->empid);

        if ($employeeInfo->cv != null) {



            $file_path = public_path('/cv') . '/' . $employeeInfo->cv;
            if (file_exists($file_path)){
                unlink($file_path);
            }

        }
        $employeeInfo->cv=null;
        $employeeInfo->save();

        Artisan::call('cache:clear');

        return $employeeInfo;


    }
    public function imageDelete(Request $r){

       // return $r->empid;

        $employeeInfo = Employee::findOrFail($r->empid);

        if ($employeeInfo->photo != null) {



            $file_path = public_path('/images') . '/' . $employeeInfo->photo;
            if (file_exists($file_path)){
                unlink($file_path);
            }

        }
        $employeeInfo->photo=null;
        $employeeInfo->save();

        Artisan::call('cache:clear');

        return $employeeInfo;


    }
    public function viewEmpInfoPdf(Request $r){




         $employee = Employee::select('employeeinfo.id as empId','employeeinfo.*',
            'designations.title as designationTitle', 'departments.departmentName','attemployeemap.attDeviceUserId',
            DB::raw("CONCAT(COALESCE(firstName,''),' ',COALESCE(middleName,''),' ',COALESCE(lastName,'')) AS empFullname"))

            ->leftjoin('designations', 'designations.id', '=', 'employeeinfo.fkDesignation')
            ->leftjoin('departments', 'departments.id', '=', 'employeeinfo.fkDepartmentId')
            ->leftjoin('attemployeemap', 'attemployeemap.employeeId', '=', 'employeeinfo.id')

            ->findOrFail($r->id);



        $file_path = public_path('/employeeInfoPDF') . '/' . 'employeeInfoPDF.pdf';

        $file_Name = 'employeeInfoPDF';

        $pdf = PDF::loadView('PDF.employeeInfo', compact('employee'))->save($file_path);

        return response()->json($file_Name);




    }

    public function storeBasicInfo(Request $r) {



        $this->validate($r, [

            'EmployeeId' => 'nullable|max:20',
            'firstName'   => 'required|max:50',
            'middleName'   => 'nullable|max:50',
            'lastName'   => 'nullable|max:50',
            'nickName'   => 'nullable|max:100',

            'email'   => 'required|max:255',
            'contactNo'   => 'nullable|max:15',
            'streetAddress'   => 'nullable|max:50',
            'apartmentUnit'   => 'nullable|max:50',
            'city'   => 'nullable|max:50',
            'state'   => 'nullable|max:50',
            'zipCode'   => 'nullable|max:50',
            'homePhone'   => 'nullable|max:50',
            'maritalStatus'   => 'nullable|max:15',
            'nationalId'   => 'nullable|max:25',
            'alterContactNo'   => 'nullable|max:15',
            'birthdate'   => 'nullable|date',
            'gender'   => 'max:1',
//            'photo'   => 'max:255',
//            'cv'   => 'max:255',

        ]);

        if ($r->id) {
            $employeeInfo = Employee::findOrFail($r->id);
        } else {

            $employeeInfo = new Employee();
            $user = new User();
            $user->email = $r->email;
            $user->userName = $r->firstName;
            $user->fkUserType = "emp";
            $user->fkCompany = auth()->user()->fkComapny;
            $user->fkActivationStatus = 1;
            $user->password = Hash::make('123456');
            $user->save();
            $employeeInfo->fkUserId = $user->id;
            $employeeInfo->createdBy = auth()->user()->id;
            $employeeInfo->fkCompany = auth()->user()->fkCompany;
            //  $employeeInfo->createdBy=1;
        }
        $employeeInfo->EmployeeId = $r->EmployeeId;
        $employeeInfo->firstName = $r->firstName;
        $employeeInfo->middleName = $r->middleName;
        $employeeInfo->lastName = $r->lastName;

        $employeeInfo->email = $r->email;
        $employeeInfo->contactNo = $r->contactNo;
        $employeeInfo->streetAddress = $r->streetAddress;
        $employeeInfo->apartmentUnit = $r->apartmentUnit;
        $employeeInfo->city = $r->city;
        $employeeInfo->state = $r->state;
        $employeeInfo->zipCode = $r->zipCode;
        $employeeInfo->homePhone = $r->homePhone;
        $employeeInfo->maritalStatus = $r->maritalStatus;
        $employeeInfo->nationalId = $r->nationalId;
        $employeeInfo->alterContactNo = $r->alterContactNo;
        $employeeInfo->birthdate = $r->birthdate;
        $employeeInfo->gender = $r->gender;
        $employeeInfo->save();

        if ($r->hasFile('photo')) {

            if ($employeeInfo->photo != null) {



                $file_path = public_path('/images') . '/' . $employeeInfo->photo;
                if (file_exists($file_path)){
                    unlink($file_path);
                }

            }

            $images = $r->file('photo');
            $name = time() . '.' . $images->getClientOriginalName();
            $destinationPath = public_path('/images');
            $images->move($destinationPath, $name);
            $employeeInfo->photo = $name;


        }
        if ($r->hasFile('cv')) {

            if ($employeeInfo->cv != null) {



                $file_path = public_path('/cv') . '/' . $employeeInfo->cv;
                if (file_exists($file_path)){
                    unlink($file_path);
                }

            }

            $images = $r->file('cv');
            $name = time() . '.' . $images->getClientOriginalName();
            $destinationPath = public_path('/cv');
            $images->move($destinationPath, $name);
            $employeeInfo->cv = $name;


        }
        $employeeInfo->save();



        Artisan::call('cache:clear');

        return Employee::findOrFail($employeeInfo->id);
    }

    public function getempDesignation(Request $r)
    {

        return $emp=Employee::select('employeeinfo.fkDepartmentId','employeeinfo.id','employeeinfo.fkDesignation',
            'designations.title as designationTitle','departments.departmentName')
            ->leftJoin('departments','departments.id','employeeinfo.fkDepartmentId')
            ->leftJoin('designations','designations.id','employeeinfo.fkDesignation')
            ->where('fkUserId',$r->id)->first();

    }

}
