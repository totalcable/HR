import {Component, OnInit, Input} from '@angular/core';
import {Constants} from '../../../constants';
import {Router} from '@angular/router';
import {TokenService} from '../../../services/token.service';
import {HttpClient} from '@angular/common/http';
declare var $: any;

@Component({
  selector: 'app-joining-info',
  templateUrl: './joining-info.component.html',
  styleUrls: ['./joining-info.component.css']
})
export class JoiningInfoComponent implements OnInit {


  department: any;
  designation: any;
  empType: any;
  previousURL: any;
  @Input('empid') empid: any;
  JoiningForm: any;

  totalLeaveAssigned: number;
  leaveTaken: number;
  temp: any;
  error = [];

  employeeJoiningForm: any = {
    id: '',
    actualJoinDate: '',
    resignDate: '',
    weekend: '',
    accessPin: '',
    employeeId: '',
    workingLocation: '',
    email_off: '',
    bloodGroup: '',

    department: '',
    designation: '',
    empType: '',
    contactNo: '',
    salary: '',

    e_name: '',
    e_street_address: '',
    e_apartment_unit: '',
    e_city: '',
    e_state: '',
    e_zip_code: '',
    e_phone: '',
    e_alternate_phone: '',
    e_relationship: '',


    attDeviceUserId: '',

    supervisor: '',
    probationPeriod: '',
    practice: '',
    fkActivationStatus: '',
    outDeviceNo: '',
    inDeviceNo: '',
  };

  // DROPDOWN
  dropdownList = [];
  selectedItems = [];
  dropdownSettings = {};

  // deviceList=[];
  // selectedDeviceList = [];
  // deviceListSettings={};

  constructor(public http: HttpClient, private token: TokenService, private router: Router) { }

  ngOnInit() {
    this.previousURL = 'employee';

    //Getting Departments
    this.http.get(Constants.API_URL + 'department/get').subscribe(data => {

        this.department = data;
      },
      error => {
        console.log(error);
      }
    );

    //Getting Designations
    this.http.get(Constants.API_URL + 'designation/get').subscribe(data => {

        this.designation = data;
      },
      error => {
        console.log(error);
      }
    );

    //Getting Employee Types
    this.http.get(Constants.API_URL + 'employee_type/get').subscribe(data => {

        this.empType = data;
      },
      error => {
        console.log(error);
      }
    );
    const token = this.token.get();

    this.dropdownList = [
      { item_id: 'saturday', item_text: 'Saturday' },
      { item_id: 'sunday', item_text: 'Sunday' },
      { item_id: 'monday', item_text: 'Monday' },
      { item_id: 'tuesday', item_text: 'Tuesday' },
      { item_id: 'wednesday', item_text: 'Wednesday' },
      { item_id: 'thursday', item_text: 'Thursday' },
      { item_id: 'friday', item_text: 'Friday' }
    ];

    this.dropdownSettings = {
      singleSelection: false,
      idField: 'item_id',
      textField: 'item_text',
      selectAllText: 'Select All',
      unSelectAllText: 'UnSelect All',
      itemsShowLimit: 3,
      allowSearchFilter: true
    };


    this.employeeJoiningForm.id = this.empid;

    this.getData();
    this.getLeaveLimit();
  }

  selectDepartment(value) {

    this.employeeJoiningForm.department = value;
  }

  selectDesignation(value) {

    this.employeeJoiningForm.designation = value;
  }

  getData() {
    const token = this.token.get();
    this.http.post(Constants.API_URL + 'joinInfo/get' + '?token=' + token, {id: this.employeeJoiningForm.id}).subscribe(data => {
        // console.log(data);
        this.JoiningForm = data;
        this.employeeJoiningForm.actualJoinDate = this.JoiningForm.actualJoinDate;


        this.employeeJoiningForm.department = this.JoiningForm.fkDepartmentId;
        this.employeeJoiningForm.employeeId = this.JoiningForm.employeeId;
        this.employeeJoiningForm.empType = this.JoiningForm.fkEmployeeType;
        this.employeeJoiningForm.designation = this.JoiningForm.fkDesignation;
        this.employeeJoiningForm.bloodGroup = this.JoiningForm.bloodGroup;

        this.employeeJoiningForm.contactNo = this.JoiningForm.contactNo;
        this.employeeJoiningForm.workingLocation = this.JoiningForm.workingLocation;
        this.employeeJoiningForm.email_off = this.JoiningForm.email_off;
        this.employeeJoiningForm.salary = this.JoiningForm.salary;

        this.employeeJoiningForm.e_name = this.JoiningForm.e_name;
        this.employeeJoiningForm.e_street_address = this.JoiningForm.e_street_address;
        this.employeeJoiningForm.e_apartment_unit = this.JoiningForm.e_apartment_unit;
        this.employeeJoiningForm.e_city = this.JoiningForm.e_city;
        this.employeeJoiningForm.e_state = this.JoiningForm.e_state;
        this.employeeJoiningForm.e_zip_code = this.JoiningForm.e_zip_code;
        this.employeeJoiningForm.e_phone = this.JoiningForm.e_phone;
        this.employeeJoiningForm.e_alternate_phone = this.JoiningForm.e_alternate_phone;
        this.employeeJoiningForm.e_relationship = this.JoiningForm.e_relationship;

        this.employeeJoiningForm.resignDate = this.JoiningForm.resignDate;
        this.employeeJoiningForm.weekend = this.JoiningForm.weekend;
        this.employeeJoiningForm.accessPin = this.JoiningForm.accessPin;
        this.employeeJoiningForm.inDeviceNo = this.JoiningForm.inDeviceNo;
        this.employeeJoiningForm.outDeviceNo = this.JoiningForm.outDeviceNo;

        this.employeeJoiningForm.fkActivationStatus = this.JoiningForm.fkActivationStatus;
        this.employeeJoiningForm.attDeviceUserId = this.JoiningForm.attDeviceUserId;
        this.employeeJoiningForm.supervisor = this.JoiningForm.supervisor;
        this.employeeJoiningForm.probationPeriod = this.JoiningForm.probationPeriod;

       //  console.log(this.employeeJoiningForm.weekend);
        if (this.employeeJoiningForm.weekend != '') {
          const weekArray = this.employeeJoiningForm.weekend.split(',');

          const tempArray = [];
          for (let i = 0; i < weekArray.length; i++) {

            tempArray.push({item_id: weekArray[i], item_text: weekArray[i].charAt(0).toUpperCase() + weekArray[i].slice(1)});

          }
          this.selectedItems = tempArray;
        }
        console.log(this.selectedItems);
      },
      error => {
        console.log(error);
      }
    );

  }


  submit() {
    if (!this.checkRequiredFields()) {
      $.alert({
        title: 'Alert!',
        type: 'Red',
        content: 'Please Insert Mandatory Fields',
        buttons: {
          tryAgain: {
            text: 'Ok',
            btnClass: 'btn-red',
            action: function () {
            }
          }
        }
      });
      return false;
    } else {
      // console.log(this.employeeJoiningForm);

      this.employeeJoiningForm.weekend = this.selectedItems;
      const token = this.token.get();
      if (this.employeeJoiningForm.actualJoinDate != null) {
        this.employeeJoiningForm.actualJoinDate = new Date(this.employeeJoiningForm.actualJoinDate).toLocaleDateString();
      }

      if (this.employeeJoiningForm.resignDate != null) {
        this.employeeJoiningForm.resignDate = new Date(this.employeeJoiningForm.resignDate).toLocaleDateString();
      }

      this.http.post(Constants.API_URL + 'joinInfo/post' + '?token=' + token, this.employeeJoiningForm).subscribe(data => {
        console.log(data);
          this.getData();
          $.alert({
            title: 'Success!',
            content: 'Updated',
          });

        },
        error => {
          const data = error.error.errors;

          for (const p in data) {
            for (const k in data[p]) {
              this.error.push(data[p][k]);
            }
          }
        }
      );

    }
  }

  submitLeaveLimit() {
    const token = this.token.get();
    this.http.post(Constants.API_URL + 'leave/limit/post' + '?token=' + token, {id: this.empid, totalLeave: this.totalLeaveAssigned, leaveTaken: this.leaveTaken}).subscribe(data => {
        this.getLeaveLimit();
        $.alert({
          title: 'Success!',
          content: 'Updated',
        });
      },
      error => {
        console.log(error);
      }
    );
  }

  checkRequiredFields() {

    if (this.employeeJoiningForm.attDeviceUserId == '' || this.employeeJoiningForm.attDeviceUserId == null) {
      return false;
    }
    if (this.employeeJoiningForm.inDeviceNo == '' || this.employeeJoiningForm.inDeviceNo == null) {
      return false;
    }
    if (this.employeeJoiningForm.department == '') {
      return false;
    }
    if (this.employeeJoiningForm.empType == '') {
      return false;
    }
    if (this.employeeJoiningForm.contactNo == '') {
      return false;
    }
    if (this.employeeJoiningForm.salary == '') {
      return false;
    }
    if (this.employeeJoiningForm.workingLocation == '') {
      return false;
    }
    if (this.employeeJoiningForm.email_off == '') {
      return false;
    }
    if (this.employeeJoiningForm.department == '') {
      return false;
    }
    if (this.employeeJoiningForm.empType == '') {
      return false;
    }
    if (this.employeeJoiningForm.employeeId == '') {
      return false;
    }
    if (this.employeeJoiningForm.designation == '') {
      return false;
    }
    if (this.employeeJoiningForm.designation == '') {
      return false;
    }
    if (this.employeeJoiningForm.contactNo == '') {
      return false;
    }
    if (this.employeeJoiningForm.outDeviceNo == '' || this.employeeJoiningForm.outDeviceNo == null) {
      return false;
    }
    return true;
  }

  getLeaveLimit() {
    const token = this.token.get();
    this.http.post(Constants.API_URL + 'leave/limit/get' + '?token=' + token, {id: this.empid}).subscribe(data => {

        this.temp = data;
        this.totalLeaveAssigned = this.temp['leaveLimit'].totalLeave;
        this.leaveTaken = this.temp['leaveTaken'];

      },
      error => {
        console.log(error);
      }
    );
  }

}
