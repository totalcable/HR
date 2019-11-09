import {Component, OnInit, AfterViewInit, Renderer, OnDestroy, ViewChild} from '@angular/core';
import {Constants} from '../../../constants';
import {HttpClient} from '@angular/common/http';
import {TokenService} from '../../../services/token.service';
import {Subject} from 'rxjs';
import {ActivatedRoute, Router} from '@angular/router';
import {DataTableDirective} from 'angular-datatables';
import {NgbModal} from '@ng-bootstrap/ng-bootstrap';
import {forEach} from '@angular/router/src/utils/collection';

declare var $: any;

@Component({
  selector: 'app-dept-wise-static-roster',
  templateUrl: './dept-wise-static-roster.component.html',
  styleUrls: ['./dept-wise-static-roster.component.css']
})
export class DeptWiseStaticRosterComponent implements OnInit {

  dropdownSettings2 = {};
  dropdownSettingsEmp = {};
  departments: any;
  selectedDropDown = [];
  selectedDropDownEmp = [];
  selectedDropDownoffDutyEmp = [];
  RosterInfo: any;
  AllRosterInfo: any;
  dayName = [];
  modalRef: any;
  employees: any;
  showbtn: boolean;


  newEmpRoster: any = {

    rosterLogId: '',
    shiftId: '',
    dayName: '',
    dutyempIds: [],
    offdutyempIds: [],

  };

  constructor(private modalService: NgbModal, private renderer: Renderer, public http: HttpClient, private token: TokenService ,
              public route: ActivatedRoute, private router: Router) {

  }

  adddiv() {
    if (this.showbtn == true) {
      this.showbtn = false;
    }
    if (this.showbtn == false) {
      this.showbtn = true;
    }
  }

  ngOnInit() {

    this.showbtn = true;

    this.dayName = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

    this.dropdownSettings2 = {
      singleSelection: true,
      idField: 'id',
      textField: 'departmentName',
    //  selectAllText: 'Select All',
     // unSelectAllText: 'UnSelect All',
      // itemsShowLimit: 3,
      allowSearchFilter: true,
      closeDropDownOnSelection: true,
    };


  //  this.getAllDepartment();
    this.getAllMultipleRosterDepartment();

  }

  // getAllDepartment() {
  //
  //   const token = this.token.get();
  //
  //
  //   this.http.get(Constants.API_URL + 'department/get' + '?token=' + token).subscribe(data => {
  //
  //       this.departments = data;
  //
  //     },
  //     error => {
  //       console.log(error);
  //     }
  //   );
  //
  // }
  getAllMultipleRosterDepartment() {

    const token = this.token.get();


    this.http.get(Constants.API_URL + 'department/getAllMultipleRosterDepartment' + '?token=' + token).subscribe(data => {

        this.departments = data;

      },
      error => {
        console.log(error);
      }
    );

  }

  searchRoster() {

    const token = this.token.get();
    const form = {
      departments: this.selectedDropDown[0]['id'],


    };

    console.log(form);

    this.http.post(Constants.API_URL + 'department/getRosterInfo' + '?token=' + token, form).subscribe(data => {

        this.AllRosterInfo = data;

        const that = this;

        that.http.post(Constants.API_URL + 'department/getStaticRosterAndEmpInfo' + '?token=' + token, form).subscribe(data1 => {

            that.RosterInfo = data1;
            console.log(data1);

          },
          error => {
            console.log(error);
          }
        );


      },
      error => {
        console.log(error);
      }
    );





  }


  ChangeRosterLog(shiftId, dayName,  content) {


    const token = this.token.get();
    const form = {
      shiftId: shiftId,
      day: dayName,

    };
    this.http.post(Constants.API_URL + 'rosterLog/getStaticRosterInfo' + '?token=' + token, form).subscribe(datas => {


      if (datas['Offduty'].length > 0) {


          for (let i = 0; i < datas['Offduty'].length; i++) {


            this.selectedDropDownoffDutyEmp.push({
              'empid': datas['Offduty'][i]['EmployeeId'],
              'empFullname': datas['Offduty'][i]['empFullname']
            });
            this.newEmpRoster.offdutyempIds.push({
              'empid': datas['Offduty'][i]['EmployeeId']
            });



          }

        }



      if (datas['duty'].length > 0) {



          for (let i = 0; i < datas['duty'].length; i++) {

            const d = {
              'empid': datas['duty'][i]['EmployeeId'],
              'empFullname': datas['duty'][i]['empFullname']
            };
            const e = {
              'empid': datas['duty'][i]['EmployeeId'],

            };
            this.selectedDropDownEmp.push(d);
            this.newEmpRoster.dutyempIds.push(e);

          }

        }

        this.newEmpRoster.shiftId = shiftId;
        this.newEmpRoster.dayName = dayName;


        this.showmodal(content);



        const deptId = [];

        for (let i = 0; i < this.selectedDropDown.length; i++) {

          deptId.push(this.selectedDropDown[0]['id']);
        }

        const form1 = {
          departments: deptId,

        };

        this.http.post(Constants.API_URL + 'employee/getAllEmpForDepartment' + '?token=' + token, form1).subscribe(data => {

            this.employees = data;

            this.dropdownSettingsEmp = {
              singleSelection: false,
              idField: 'empid',
              textField: 'empFullname',
              // selectAllText: 'Select All',
              // unSelectAllText: 'UnSelect All',
              // itemsShowLimit: 3,
              allowSearchFilter: true,
              closeDropDownOnSelection: true,
            };


          },
          error => {
            console.log(error);
          }
        );


      },
      error => {
        console.log(error);
      }
    );






  }
  onSelect(event) {

    this.newEmpRoster.dutyempIds = [];
    this.newEmpRoster.offdutyempIds = [];



    if (this.selectedDropDownEmp.length > 0) {


      for (let i = 0; i < this.selectedDropDownEmp.length; i++) {


          this.newEmpRoster.dutyempIds.push({
            'empid': this.selectedDropDownEmp[i]['empid'],
          });

      }

    }
    if (this.selectedDropDownoffDutyEmp.length > 0) {


      for (let i = 0; i < this.selectedDropDownoffDutyEmp.length; i++) {


          this.newEmpRoster.offdutyempIds.push({
            'empid': this.selectedDropDownoffDutyEmp[i]['empid'],
          });

      }

    }


  }

   showmodal(content) {
    this.modalRef = this.modalService.open(content, {size: 'lg', backdrop: 'static'});
  }
   modalClose() {

    this.selectedDropDownEmp = [];
    this.newEmpRoster = {
      rosterLogId: '',
      shiftId: '',
      dayName: '',
      dutyempIds: [],
      offdutyempIds: [],
    };

    this.selectedDropDownoffDutyEmp = [];

    this.modalRef.close();

  }

  updateRoster() {

    const token = this.token.get();

   // console.log(this.newEmpRoster);



    this.http.post(Constants.API_URL + 'rosterLog/setStaticRosterInfo' + '?token=' + token, this.newEmpRoster).subscribe(data => {

      console.log(data);



        $.alert({
          title: data,
          content: 'Static Roster set Successfully',
        });
        this.modalClose();
        this.searchRoster();


      },
      error => {
        console.log(error);
      }
    );

  }

}
