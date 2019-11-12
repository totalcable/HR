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
  selector: 'app-punch-time-edit',
  templateUrl: './punch-time-edit.component.html',
  styleUrls: ['./punch-time-edit.component.css']
})
export class PunchTimeEditComponent implements OnInit {

  employee: any;
  dropdownSettings = {};
  selectedItems = [];
  date: any;
  empRoster: any;
  modalRef: any;
  inDevice: any;
  outDevice: any;
  inTime: any;
  outTime: any;
  insertDate: any;

  constructor(private modalService: NgbModal, private renderer: Renderer, public http: HttpClient,
              private token: TokenService , public route: ActivatedRoute, private router: Router) { }

  ngOnInit() {
    this.getAllEployee();

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

  }

  getAllEployee() {

    const token = this.token.get();


    this.http.get(Constants.API_URL + 'employee/getAll' + '?token=' + token).subscribe(data => {
        this.employee = data;

      },
      error => {
        console.log(error);
      }
    );

  }

  findRosterAndPunch() {

    const token = this.token.get();

    const form = {
      'empId': this.selectedItems[0]['empid'],
      'date': $('#date').val()
    };


    this.http.post(Constants.API_URL + 'punch/getEmpRosterAndPunches' + '?token=' + token, form).subscribe(data => {
        this.empRoster = data;
      if (this.empRoster.length == 0) {

        $.alert({
          title: 'Alert!',
          type: 'Red',
          content: 'there is no roster for this employee on this day',
          buttons: {
            tryAgain: {
              text: 'Ok',
              btnClass: 'btn-red',
              action: function () {
              }
            }
          }
        });

      }


      // console.log(data);

      },
      error => {
        console.log(error);
      }
    );

  }
  AddPunch(inTime, outTime, punchTemplate) {

    const token = this.token.get();

    const form = {
      'empId': this.selectedItems[0]['empid'],

    };
    this.inTime = inTime;
    this.outTime = outTime;
    this.insertDate = $('#date').val();


    this.http.post(Constants.API_URL + 'punch/getEmployeeINandOUTdevice' + '?token=' + token, form).subscribe(data => {

         this.inDevice = data['inDeviceNo'];
         this.outDevice = data['outDeviceNo'];


      },
      error => {
        console.log(error);
      }
    );

    this.modalRef = this.modalService.open(punchTemplate, {size: 'lg', backdrop: 'static'});

  }
  add() {

  if (!this.checkForm()) {
  return false;
  } else {

    const token = this.token.get();

    if (this.inDevice == $('#deviceNumber').val()) {

      const form = {
        'empId': this.selectedItems[0]['empid'],
        'dateFormate': this.insertDate,
        'timeFormate': $('#addTime').val(),
        'deviceNumber': $('#deviceNumber').val(),
        'inTime': $('#inTime').val(),
        'outTime': $('#outTime').val(),
        'deviceChk': 'in',
      };

      this.http.post(Constants.API_URL + 'punch/addPunches' + '?token=' + token, form).subscribe(data1 => {

         // console.log(data1);

          if (data1 == '1') {

            $.alert({
              title: 'Alert!',
              type: 'green',
              content: 'Punch Added Successfully',
              buttons: {
                tryAgain: {
                  text: 'Ok',
                  btnClass: 'btn-green',
                  action: function () {
                  }
                }
              }
            });

            this.modalClose();


          } else {

            $.alert({
              title: 'Alert!',
              type: 'Red',
              content: data1,
              buttons: {
                tryAgain: {
                  text: 'Ok',
                  btnClass: 'btn-red',
                  action: function () {
                  }
                }
              }
            });


          }



        },
        error => {
          console.log(error);
        }
      );

    } else if (this.outDevice == $('#deviceNumber').val()) {

      const form = {
        'empId': this.selectedItems[0]['empid'],
        'dateFormate': this.insertDate,
        'timeFormate': $('#addTime').val(),
        'deviceNumber': $('#deviceNumber').val(),
        'inTime': $('#inTime').val(),
        'outTime': $('#outTime').val(),
        'deviceChk': 'out',
      };

      this.http.post(Constants.API_URL + 'punch/addPunches' + '?token=' + token, form).subscribe(data1 => {


          if (data1 == '1') {

            $.alert({
              title: 'Alert!',
              type: 'green',
              content: 'Punch Added Successfully',
              buttons: {
                tryAgain: {
                  text: 'Ok',
                  btnClass: 'btn-green',
                  action: function () {
                  }
                }
              }
            });
            this.modalClose();

          } else {

            $.alert({
              title: 'Alert!',
              type: 'Red',
              content: data1,
              buttons: {
                tryAgain: {
                  text: 'Ok',
                  btnClass: 'btn-red',
                  action: function () {
                  }
                }
              }
            });


          }



        },
        error => {
          console.log(error);
        }
      );

    }






  }

  }
  checkForm() {
    let message = '';
    let condition = true;


    if (this.selectedItems.length == 0) {

      condition = false;
      message = 'Please Select an Employee';

    }

    if ($('#date').val() == '') {

      condition = false;
      message = 'Please select a date';

    }
    if ($('#time').val() == '') {

      condition = false;
      message = 'Please select a Time';

    }
    if ($('#deviceNumber').val() == '') {

      condition = false;
      message = 'Please select a Device ';

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
  modalClose() {
    this.modalRef.close();
    this.inDevice = '';
    this.outDevice = '';
    this.inTime = '';
    this.outTime = '';
    this.insertDate = '';
  }


  // empPunches(shiftLogId) {
  //
  //   const token = this.token.get();
  //
  //   const form = {
  //     'empId': this.selectedItems[0]['empid'],
  //     'date': $('#date').val(),
  //     'shiftLog': shiftLogId
  //   };
  //
  //
  //   this.http.post(Constants.API_URL + 'punch/getEmpPunches' + '?token=' + token, form).subscribe(data => {
  //
  //       this.empRoster = data;
  //
  //     },
  //     error => {
  //       console.log(error);
  //     }
  //   );
  //
  // }



}
