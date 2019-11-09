import {Component, OnInit, AfterViewInit, Renderer, OnDestroy, ViewChild} from '@angular/core';
import {Constants} from '../../../constants';
import {HttpClient} from '@angular/common/http';
import {TokenService} from '../../../services/token.service';
import {Subject} from 'rxjs';
import {ActivatedRoute, Router} from '@angular/router';
import {DataTableDirective} from 'angular-datatables';
declare var $: any;

@Component({
  selector: 'app-shift-assign',
  templateUrl: './shift-assign.component.html',
  styleUrls: ['./shift-assign.component.css']
})
export class ShiftAssignComponent implements AfterViewInit, OnDestroy, OnInit {
  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;
  employee: any;
  dtOptions: DataTables.Settings = {};
  dtTrigger: Subject<any> = new Subject();
  id: any;
  allEmp = [];
  team: any;
  shiftId: number;
  shift: any;
  dtInstance: DataTables.Api;
  startDate: string;
  endDate: string;
  // DROPDOWN
  dropdownList = [];
  selectedItems = [];
  dropdownSettings = {};
  dropdownSettings2 = {};
  dates: any;
  showAssign: boolean;
  shiftLog = [];
  newShiftLog = [];


constructor(private renderer: Renderer, public http: HttpClient, private token: TokenService , public route: ActivatedRoute, private router: Router,
            ) { }

  ngOnInit() {

    this.dropdownList = [
      { item_id: 'saturday', item_text: 'Saturday' },
      { item_id: 'sunday', item_text: 'Sunday' },
      { item_id: 'monday', item_text: 'Monday' },
      { item_id: 'tuesday', item_text: 'Tuesday' },
      { item_id: 'wednesday', item_text: 'Wednesday' },
      { item_id: 'thursday', item_text: 'Thursday' },
      { item_id: 'friday', item_text: 'Friday' }
    ];
    this.dropdownSettings2 = {
      singleSelection: true,
      idField: 'shiftId',
      textField: 'shiftName',
      // selectAllText: 'Select All',
      // unSelectAllText: 'UnSelect All',
      // itemsShowLimit: 3,
      allowSearchFilter: true,
      closeDropDownOnSelection: true
    };

    this.dropdownSettings = {
      singleSelection: false,
      idField: 'item_id',
      textField: 'item_text',
      selectAllText: 'Select All',
      unSelectAllText: 'UnSelect All',
      itemsShowLimit: 3,
      allowSearchFilter: true
    };
    this.getData();
    this.getShift();

    this.dates = [];
    this.showAssign = false;


  }
  onItemSelect(value) {
    // console.log(value);
  }


  onSelectAll(value) {
    // console.log(value);
  }

  getShift() {
    const token = this.token.get();

    this.http.get(Constants.API_URL + 'shift/get' + '?token=' + token).subscribe(data => {
        this.shift = data;
        // console.log(data);
      },
      error => {
        console.log(error);
      }
    );

  }

  getData() {
    const token = this.token.get();
    this.dtOptions = {
      ajax: {
        url: Constants.API_URL + 'employee/shift/get' + '?token=' + token,
        type: 'POST',
        data: function (d: any) {
          d.teamId = $('#team').val();
        },
      },
      columns: [
        {

          'data': function (data: any, type: any, full: any) {
            return '<input type="checkbox" class="chk form-control" name="selected_rows[]" value="' + data.empid + '" data-panel-id="' + data.empid + '">';
          },
          'orderable': false, 'searchable': false, 'name': 'selected_rows'
        },
        { data: 'firstName' , name: 'employeeinfo.firstName'},
        { data: 'middleName' , name: 'employeeinfo.middleName'},
        { data: 'lastName' , name: 'employeeinfo.lastName'},
        { data: 'EmployeeId' , name: 'employeeinfo.EmployeeId' },


      ],

      processing: true,
      serverSide: true,
      pagingType: 'full_numbers',
      pageLength: 50
    };
  }

  ngAfterViewInit(): void {
    this.dtTrigger.next();

  }
  ngOnDestroy(): void {
    // Do not forget to unsubscribe the event
    this.dtTrigger.unsubscribe();
  }


  selectAll() {
    this.allEmp = [];
    if ($('#selectall2').is(':checked')) {

      const  checkboxes = document.getElementsByName('selected_rows[]');

      $('input:checkbox').prop('checked', true);

    } else {

      $(':checkbox:checked').prop('checked', false);
    }

  }

  selectShift(value, value2) {

    this.shiftId = value.shiftId;


      this.shiftLog = [{
        date: value2, shift: value.shiftId

      }];
    for (let i = 0; i < this.newShiftLog.length; i++) {
      if (this.newShiftLog[i][0].date == value2) {
        this.newShiftLog.splice(i, 1);
        break;
      }
    }
    if (value.shiftId != '') {
      this.newShiftLog.push(this.shiftLog);
    }

      console.log(this.newShiftLog);


  }


  assignShift() {
    const that = this;

    this.allEmp = [];
    $('.chk:checked').each(function () {
      that.allEmp.push($(this).val());
    });



    if (this.newShiftLog == null || this.startDate == null || this.allEmp.length == 0 || this.selectedItems.length == 0) {
      alert('Empty');
    } else {
      // new Date(this.employeeJoiningForm.actualJoinDate).toLocaleDateString();

      const form = {
        allEmp: this.allEmp,
        newShiftLog: this.newShiftLog,
        startDate: new Date(this.startDate).toLocaleDateString(),
        weekends: this.selectedItems
      };
      const token = this.token.get();

      this.http.post(Constants.API_URL + 'shift/assign' + '?token=' + token, form).subscribe(data => {
          console.log(data);

          $.alert({
            title: data,
            content: 'Shift Assign Successfull',
          });

          this.rerender();



        },
        error => {
          console.log(error);
        }
      );
    }


  }

  selectTeam() {
    this.rerender();
  }
  rerender() {
    this.dtElement.dtInstance.then((dtInstance: DataTables.Api) => {

      dtInstance.destroy();

      this.dtTrigger.next();
    });
  }
  getDayWithName() {
    if (this.startDate == null || this.endDate == null) {
      alert('Empty');
    } else {
      const form = {

        startDate: new Date(this.startDate).toLocaleDateString(),
        endDate: new Date(this.endDate).toLocaleDateString(),

      };
      const token = this.token.get();

      this.http.post(Constants.API_URL + 'dateRanges' + '?token=' + token, form).subscribe(data => {
          this.dates = data;
          this.showAssign = true;
          console.log(this.showAssign);


        },
        error => {
          console.log(error);
        }
      );

    }
  }



}

