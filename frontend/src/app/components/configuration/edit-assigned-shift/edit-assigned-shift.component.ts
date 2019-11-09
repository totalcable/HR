import {Component, OnInit, AfterViewInit, Renderer, OnDestroy, ViewChild} from '@angular/core';
import {Constants} from '../../../constants';
import {HttpClient} from '@angular/common/http';
import {TokenService} from '../../../services/token.service';
import {Subject} from 'rxjs';
import {ActivatedRoute, Router} from '@angular/router';
import {DataTableDirective} from 'angular-datatables';
import {NgbModal} from '@ng-bootstrap/ng-bootstrap';


declare var $: any;

@Component({
  selector: 'app-edit-assigned-shift',
  templateUrl: './edit-assigned-shift.component.html',
  styleUrls: ['./edit-assigned-shift.component.css'],

})
export class EditAssignedShiftComponent implements OnInit {
  employee: any;
  assignedLog: any;
  dropdownSettings = {};
  dropdownSettings2 = {};
  selectedItems = [];
  selectedItems2 = [];
  selectedItems3 = [];
  newSelectedItems2 = [];
  empId: number;
  startDate: string;
  endDate: string;
  dates: any;
  shift: any;
  notAssignedView: boolean;
  shiftObj: any = {
    shiftLogId: '',
    shiftId: '',
    empId: '',
    date: '',
    inTime: '',
    outTime: '',
    deviceUserId: '',
    adjustment: '',
    adjustmentDate: '',
    leave: '',
  };
  futureShift: any = {

    empId: '',
    startDate: '',
    endDate: '',
    futureStartDate: '',
    futureEndDate: '',
  };
  AdjustmentCheckBox = false;
  LeaveCheckBox = false;
  modalRef: any;

  constructor(private modalService: NgbModal, private renderer: Renderer, public http: HttpClient, private token: TokenService , public route: ActivatedRoute, private router: Router) {
    this.notAssignedView = false;

  }

  ngOnInit() {

    this.route.params.subscribe(params => {
      const id = params['id'];
      const start = params['start'];
      const end = params['end'];
      const userId = params['userId'];

      if (typeof id === 'undefined' || id === null) {

      } else {
        this.notAssignedinfo(id, userId, start, end);
        this.notAssignedView = true;
      }

    });


   // this.getAllEployee();
    this.getOnlySingleRosterDepartmentEployee();

    this.getShift();
    this.dates = [];

    this.dropdownSettings = {
      singleSelection: true,
      idField: 'empid',
      textField: 'attDeviceUserId',
      // selectAllText: 'Select All',
      // unSelectAllText: 'UnSelect All',
      // itemsShowLimit: 3,
      allowSearchFilter: true,
      closeDropDownOnSelection: true,
    };

    this.dropdownSettings2 = {
      singleSelection: true,
      idField: 'shiftId',
      textField: 'shiftName',
      // selectAllText: 'Select All',
      // unSelectAllText: 'UnSelect All',
      // itemsShowLimit: 3,
      allowSearchFilter: true,
      closeDropDownOnSelection: true,
    };
  }
  toggleAdjustment(e) {
  this.AdjustmentCheckBox = e.target.checked;
    if (this.AdjustmentCheckBox == false) {
        this.shiftObj.adjustmentDate = '';
    }
  }
  toggleLeave(e) {
  this.LeaveCheckBox = e.target.checked;
  }
  // getAllEployee() {
  //
  //   const token = this.token.get();
  //
  //
  //   this.http.get(Constants.API_URL + 'employee/getAll' + '?token=' + token).subscribe(data => {
  //       this.employee = data;
  //        console.log(data);
  //     },
  //     error => {
  //       console.log(error);
  //     }
  //   );
  //
  // }
  getOnlySingleRosterDepartmentEployee() {

    const token = this.token.get();


    this.http.get(Constants.API_URL + 'employee/getAllSingleRosterDepartmentEployee' + '?token=' + token).subscribe(data => {
        this.employee = data;
         console.log(data);
      },
      error => {
        console.log(error);
      }
    );

  }
  weekend(shiftLogId, date, empId, text) {

    const that = this;
    const d = date;
    const e = this.selectedItems[0]['empid'];
    const l = shiftLogId;
    const direction = text;

    $.confirm({
      title: 'Confirm!',
      content: 'Are you sure?',
      buttons: {
        confirm: function () {

          that.setWeekend(l, d, e, direction);

        },
        cancel: function () {

        }
      }
    });


  }
  holiday(shiftLogId, date, empId, text) {

    const that = this;
    const d = date;
    const e = this.selectedItems[0]['empid'];
    const l = shiftLogId;
    const direction = text;

    $.confirm({
      title: 'Confirm!',
      content: 'Are you sure?',
      buttons: {
        confirm: function () {

          that.setHoliday(l, d, e, direction);

        },
        cancel: function () {

        }
      }
    });


  }
  setHoliday(shiftLogId, date, empId, direction) {

    const form = {
      empId: empId,
      date: date,
      shiftLogId: shiftLogId,
      direction: direction,

    };
    const token = this.token.get();

    this.http.post(Constants.API_URL + 'shiftLogHoliday/setHoliday' + '?token=' + token, form).subscribe(data1 => {

        $.alert({
          title: 'Success',
          content: 'Update Successfull',
        });

        this.route.params.subscribe(params => {
          const id = params['id'];
          const start = params['start'];
          const end = params['end'];
          const userId = params['userId'];

          if (typeof id === 'undefined' || id === null) {

            this.findAttendence();


          } else {
            this.notAssignedinfo(id, userId, start, end);
          }

        });



      },
      error => {
        console.log(error);
      }
    );


  }
  setWeekend(shiftLogId, date, empId, direction) {

    const form = {
      empId: empId,
      date: date,
      shiftLogId: shiftLogId,
      direction: direction,

    };
    const token = this.token.get();

    this.http.post(Constants.API_URL + 'shiftLogWeekend/setWeekend' + '?token=' + token, form).subscribe(data1 => {

        $.alert({
          title: 'Success',
          content: 'Update Successfull',
        });

        this.route.params.subscribe(params => {
          const id = params['id'];
          const start = params['start'];
          const end = params['end'];
          const userId = params['userId'];

          if (typeof id === 'undefined' || id === null) {

            this.findAttendence();


          } else {
            this.notAssignedinfo(id, userId, start, end);
          }

        });



      },
      error => {
        console.log(error);
      }
    );


  }
  submitFuture() {
    const form = {
      empId: this.selectedItems[0]['empid'],
      startDate: new Date(this.futureShift.startDate).toLocaleDateString(),
      endDate: new Date(this.futureShift.endDate).toLocaleDateString(),
      futureStartDate: new Date(this.futureShift.futureStartDate).toLocaleDateString(),
      futureEndDate: new Date(this.futureShift.futureEndDate).toLocaleDateString(),

    };
    // this.futureShift.empId=this.selectedItems[0]['empid'];
    // this.futureShift.startDate=new Date(this.futureShift.startDate).toLocaleDateString();
    // this.futureShift.endDate=new Date(this.futureShift.endDate).toLocaleDateString();
    // this.futureShift.futureStartDate=new Date(this.futureShift.futureStartDate).toLocaleDateString();
    // this.futureShift.futureEndDate=new Date(this.futureShift.futureEndDate).toLocaleDateString();




    const token = this.token.get();

    this.http.post(Constants.API_URL + 'shift/AssignFutureShift' + '?token=' + token, form).subscribe(data4 => {

     // this.findAttendence();
      console.log(data4);

      $.alert({
          title: 'Success',
          content: 'Update Successfull',
        });


        this.selectedItems2 = [];
        this.shiftObj.adjustment = false;
        this.modalRef.close();
        this.futureShift = [];
        this.findAttendence();





      },
      error => {
        console.log(error);
      }
    );



  }
  onItemSelect(value) {

    this.assignedLog = [];
     console.log(this.selectedItems);

  }
  onItemDeSelect(value) {

    this.assignedLog = [];

  }
  onItemSelect2(value) {

   // console.log(value.shiftId);
    this.shiftObj.inTime = '';
    this.shiftObj.outTime = '';
    //this.selectedItems2=value;
    const index = this.selectedItems2.indexOf(value.shiftId);

    if (index > -1) {
      this.selectedItems2.splice(index, 1);

    } else {
      this.selectedItems2 = [];
      this.selectedItems2.push(value.shiftId);
    }

    if (this.selectedItems2.length > 0) {

      const token = this.token.get();

      this.http.get(Constants.API_URL + 'shift/getInfo/' + value.shiftId + '?token=' + token).subscribe(data => {

          this.shiftObj.inTime = data['inTime'];
          this.shiftObj.outTime = data['outTime'];


        },
        error => {
          console.log(error);
        }
      );

    } else {
      this.shiftObj.inTime = '';
      this.shiftObj.outTime = '';
    }


    //console.log(this.selectedItems2);

  }
  onSelectAll2(value) {
    this.selectedItems2 = [];
    this.shiftObj.inTime = '';
    this.shiftObj.outTime = '';
    for (let i = 0; i < value.length; i++) {

      this.selectedItems2.push(value[i].shiftId);

    }
   // console.log(this.selectedItems2);

  }
  onDeSelectAll2(value) {
    this.selectedItems2 = [];
    this.shiftObj.inTime = '';
    this.shiftObj.outTime = '';

    }

  findAttendence() {

    if (this.startDate == null || this.endDate == null || this.selectedItems.length == 0) {
      alert('Empty');
    } else {

      const form = {
        empId: this.selectedItems[0]['empid'],
        startDate: new Date(this.startDate).toLocaleDateString(),
        endDate: new Date(this.endDate).toLocaleDateString(),

      };
      const token = this.token.get();

      this.http.post(Constants.API_URL + 'dateRanges/AssignedShift' + '?token=' + token, form).subscribe(data1 => {
          this.assignedLog = data1;
        //  console.log(data1);



        },
        error => {
          console.log(error);
        }
      );


    }


  }
  notAssignedinfo(id, userId, start, end) {

    const r = {'empid': id, 'attDeviceUserId': userId};
    this.selectedItems.push(r);


    const s = new Date(start);
    const e = new Date(end);
    const formatted_Startdate = s.getFullYear() + '-' + (s.getMonth() + 1) + '-' + s.getDate();
    const formatted_Enddate = e.getFullYear() + '-' + (e.getMonth() + 1) + '-' + e.getDate();

    this.startDate = formatted_Startdate;
    this.endDate = formatted_Enddate;

      const form = {
        empId: id,
        startDate: new Date(this.startDate).toLocaleDateString(),
        endDate: new Date(this.endDate).toLocaleDateString(),

      };
      const token = this.token.get();

      this.http.post(Constants.API_URL + 'dateRanges/NotAssignedShiftPerEmp' + '?token=' + token, form).subscribe(data1 => {
          this.assignedLog = data1;
          //console.log(data1);

        },
        error => {
          console.log(error);
        }
      );



  }
  changeAssignShift() {

    if ( this.shiftObj.empId == null) {
      alert('Empty');
    }

    if (!this.checkForm2()) {
      return false;
    } else {



      if (this.shiftObj.adjustmentDate == '' || this.shiftObj.adjustmentDate == null ) {

        const form = {

          empId: this.shiftObj.empId,
          date: this.shiftObj.date,
          shiftLogId: this.shiftObj.shiftLogId,
          shiftId: this.selectedItems2[0],
          inTime: this.shiftObj.inTime,
          outTime: this.shiftObj.outTime,
          adjustment: this.shiftObj.adjustment,
          adjustmentDate: '',
          leave: this.shiftObj.leave,


        };

        console.log(form);


        const token = this.token.get();

        this.http.post(Constants.API_URL + 'shift/assigned-shift-update' + '?token=' + token, form).subscribe(data => {
            console.log(data);

            $.alert({
              title: data,
              content: 'Update Successfull',
            });

            this.route.params.subscribe(params => {
              const id = params['id'];
              const start = params['start'];
              const end = params['end'];
              const userId = params['userId'];

              if (typeof id === 'undefined' || id === null) {

                this.findAttendence();


              } else {
                this.notAssignedinfo(id, userId, start, end);
              }

            });
            this.selectedItems2 = [];
            this.shiftObj.adjustment = false;
            this.modalRef.close();







          },
          error => {
            console.log(error);
          }
        );

      } else {

        const form = {

          empId: this.shiftObj.empId,
          date: this.shiftObj.date,
          shiftLogId: this.shiftObj.shiftLogId,
          shiftId: this.selectedItems2[0],
          inTime: this.shiftObj.inTime,
          outTime: this.shiftObj.outTime,
          adjustment: this.shiftObj.adjustment,
          adjustmentDate: new Date(this.shiftObj.adjustmentDate).toLocaleDateString(),
          leave: this.shiftObj.leave,


        };

        console.log(form);
        const token = this.token.get();

        this.http.post(Constants.API_URL + 'shift/assigned-shift-update' + '?token=' + token, form).subscribe(data => {
            console.log(data);

            $.alert({
              title: data,
              content: 'Update Successfull',
            });
            this.findAttendence();
            this.selectedItems2 = [];
            this.shiftObj.adjustment = false;
            this.modalRef.close();





          },
          error => {
            console.log(error);
          }
        );

      }




    }


  }
  getShift() {
    const token = this.token.get();

    this.http.get(Constants.API_URL + 'shift/get' + '?token=' + token).subscribe(data => {
        this.shift = data;

      },
      error => {
        console.log(error);
      }
    );

  }

  edit(shiftlogid, date, empId, content) {

    if (shiftlogid != null) {

      $.alert({
        title: 'Roster Found',
        content: 'Please delete the existing roster first',
      });

    } else {

      let i = 0;
      for (i; i < this.assignedLog.length; i++) {
        if (this.assignedLog[i].shiftLogId == shiftlogid) {

          this.shiftObj.shiftLogId = shiftlogid;
          this.shiftObj.shiftId = this.assignedLog[i].shiftId;
          this.shiftObj.empId = this.selectedItems[0]['empid'];
          this.shiftObj.date = date;
          this.shiftObj.inTime = this.assignedLog[i].inTime;
          this.shiftObj.outTime = this.assignedLog[i].outTime;
          this.shiftObj.deviceUserId = this.assignedLog[i].attDeviceUserId;
          break;
        }
      }
      console.log(this.assignedLog);
      console.log(shiftlogid);
      this.modalRef = this.modalService.open(content, {size: 'lg', backdrop: 'static'});

    }

  }
  viewFutureRosterForm(future) {

    this.shiftObj.empId = this.selectedItems[0]['empid'];


    this.modalRef = this.modalService.open(future, {size: 'lg', backdrop: 'static'});

  }

  editShiftLog(shiftlogid, date, empId, content) {


      let i = 0;
      for (i; i < this.assignedLog.length; i++) {
        if (this.assignedLog[i].shiftLogId == shiftlogid) {

          this.shiftObj.shiftLogId = shiftlogid;
          this.shiftObj.shiftId = this.assignedLog[i].shiftId;
          this.shiftObj.empId = this.selectedItems[0]['empid'];
          this.shiftObj.date = date;
          this.shiftObj.inTime = this.assignedLog[i].inTime;
          this.shiftObj.outTime = this.assignedLog[i].outTime;
          this.shiftObj.deviceUserId = this.assignedLog[i].attDeviceUserId;
          break;
        }
      }
      console.log(this.assignedLog);
      console.log(shiftlogid);
      this.modalRef = this.modalService.open(content, {size: 'lg', backdrop: 'static'});

  }
  AdjustmentShiftLog(shiftlogid, date, empId, adjustment) {


    if (shiftlogid == null) {
      $.alert({
        title: 'Alert',
        content: 'There is no shit for this user on this day',
      });
    } else {

      let i = 0;
      for (i; i < this.assignedLog.length; i++) {
        if (this.assignedLog[i].shiftLogId == shiftlogid) {

          this.shiftObj.shiftLogId = shiftlogid;
          this.shiftObj.shiftId = this.assignedLog[i].shiftId;
          this.selectedItems2 = this.assignedLog[i].shiftId;

          this.shiftObj.empId = this.selectedItems[0]['empid'];
          this.shiftObj.date = date;
          this.shiftObj.inTime = this.assignedLog[i].inTime;
          this.shiftObj.outTime = this.assignedLog[i].outTime;
          this.shiftObj.deviceUserId = this.assignedLog[i].attDeviceUserId;
          break;
        }
      }


      console.log(this.shiftObj.shiftId);
      this.modalRef = this.modalService.open(adjustment, {size: 'lg', backdrop: 'static'});


    }




  }
  onItemSelect3(value) {

    const index = this.selectedItems3.indexOf(value.shiftId);

    if (index > -1) {
      this.selectedItems3.splice(index, 1);

    } else {
      this.selectedItems3 = [];
      this.selectedItems3.push(value.shiftId);
    }
    console.log(this.selectedItems3);


    // if (this.selectedItems3 == value.shiftId) {
    //   this.selectedItems3=[];
    //
    // }else {
    //   this.selectedItems3=value.shiftId;
    // }

    const token = this.token.get();

    this.http.get(Constants.API_URL + 'shift/getInfo/' + value.shiftId + '?token=' + token).subscribe(data => {

      this.shiftObj.inTime = data['inTime'];
      this.shiftObj.outTime = data['outTime'];



      },
      error => {
        console.log(error);
      }
    );


  }

  submitAdjustment() {

    if (!this.checkForm()) {
      return false;
    }

    if (this.shiftObj.adjustmentDate == '' || this.shiftObj.adjustmentDate == null) {

      const form = {
        empId: this.selectedItems[0]['empid'],
        date: this.shiftObj.date,
        shiftLogId: this.shiftObj.shiftLogId,
        shiftId: this.selectedItems3,
        adjustmentDate: '',
        outTime: this.shiftObj.outTime,
        inTime: this.shiftObj.inTime,

      };
      const token = this.token.get();

      this.http.post(Constants.API_URL + 'shift/adjustmentAdd' + '?token=' + token, form).subscribe(data => {


          $.alert({
            title: 'Success!',
            type: 'Green',
            content: 'Adjustment updated',
            buttons: {
              tryAgain: {
                text: 'Ok',
                btnClass: 'btn-red',
                action: function () {

                }
              }
            }
          });
          this.findAttendence();
          this.selectedItems2 = [];
          this.shiftObj.adjustment = false;
          this.modalRef.close();

        },
        error => {
          console.log(error);
        }
      );

    } else {
      const form = {
        empId: this.selectedItems[0]['empid'],
        date: this.shiftObj.date,
        shiftLogId: this.shiftObj.shiftLogId,
        shiftId: this.selectedItems3,
        adjustmentDate: new Date(this.shiftObj.adjustmentDate).toLocaleDateString(),
        outTime: this.shiftObj.outTime,
        inTime: this.shiftObj.inTime,

      };

      const token = this.token.get();

      this.http.post(Constants.API_URL + 'shift/adjustmentAdd' + '?token=' + token, form).subscribe(data => {
          // console.log(data);

          $.alert({
            title: 'Success!',
            type: 'Green',
            content: 'Adjustment updated',
            buttons: {
              tryAgain: {
                text: 'Ok',
                btnClass: 'btn-red',
                action: function () {

                }
              }
            }
          });
          this.findAttendence();
          this.selectedItems2 = [];
          this.shiftObj.adjustment = false;
          this.modalRef.close();

        },
        error => {
          console.log(error);
        }
      );

    }





  }

  checkForm() {
    let message = '';
    let condition = true;

    if (this.selectedItems3.length == 0) {

      if (this.shiftObj.inTime == '' ) {

        condition = false;
        message = 'Please insert inTime';

      }
      if (this.shiftObj.outTime == '' ) {

        condition = false;
        message = 'Please insert outTime';

      }

    }
    // if (this.shiftObj.adjustmentDate==""){
    //     condition=false;
    //     message="Please insert adjustment date";
    // }

    if (condition == false) {
      $.alert({
        title: 'Alert!',
        type: 'Red',
        content: message,
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

    }

    return true;

  }
  checkForm2() {
    let message = '';
    let condition = true;

    if (this.selectedItems2.length == 0) {

      if (this.shiftObj.inTime == '' || this.shiftObj.inTime == null ) {

        condition = false;
        message = 'Please insert inTime';

      }
      if (this.shiftObj.outTime == '' || this.shiftObj.outTime == null ) {

        condition = false;
        message = 'Please insert outTime';

      }

    }
    if (this.shiftObj.adjustment == true) {

      if (this.shiftObj.adjustmentDate == '' || this.shiftObj.adjustmentDate == null) {
        condition = false;
        message = 'Please insert adjustment date';
      }

    }


    if (condition == false) {

      $.alert({
        title: 'Alert!',
        type: 'Red',
        content: message,
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

    }

    return true;

  }
  confirmDelete(shiftlogid, date, empId) {


    const that = this;
    const t = shiftlogid;
    const d = date;
    const e = empId;


      $.confirm({
        title: 'Confirm!',
        content: 'Are you sure to delete this roster!',
        buttons: {
          confirm: function () {

            that.delete(t, d, e);

          },
          cancel: function () {

          }
        }
      });
  }
  delete(shiftlogid, date, empId) {

    let i = 0;
    for (i; i < this.assignedLog.length; i++) {
      if (this.assignedLog[i].shiftLogId == shiftlogid) {

        this.shiftObj.shiftLogId = shiftlogid;
        this.shiftObj.shiftId = this.assignedLog[i].shiftId;
        this.shiftObj.empId = this.selectedItems[0]['empid'];
        this.shiftObj.date = this.assignedLog[i].date;
        break;
      }
    }

    if (shiftlogid == null) {
      $.alert({
        title: 'Alert',
        content: 'There is no shit for this user on this day',
      });
    } else {

      const form = {
        empId: this.selectedItems[0]['empid'],
        date: this.shiftObj.date,
        shiftLogId: this.shiftObj.shiftLogId,



      };

      const token = this.token.get();

      this.http.post(Constants.API_URL + 'shift/assigned-shift-delete' + '?token=' + token, form).subscribe(data => {
          console.log(data);

          $.alert({
            title: data,
            content: 'Update Successfull',
          });

          this.findAttendence();


        },
        error => {
          console.log(error);
        }
      );




    }


  }
  private modalClose() {

    this.shiftObj = {};
    this.selectedItems2 = [];
    this.futureShift = [];
    this.shiftObj.adjustment = false;
    this.modalRef.close();

  }
  reload() {
    this.router.navigate(['configuration/shift/edit-assign']);
  }

  // openLg(content) {
  //   this.shiftObj={};
  //   this.modalRef =  this.modalService.open(content, { size: 'lg'});
  //
  // }

}
