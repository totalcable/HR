import {Component, OnInit, AfterViewInit, Renderer, OnDestroy, ViewChild} from '@angular/core';
import {Constants} from '../../../constants';
import {HttpClient} from '@angular/common/http';
import {TokenService} from '../../../services/token.service';
import {Subject} from 'rxjs';
import {ActivatedRoute, Router} from '@angular/router';
import {DataTableDirective} from 'angular-datatables';
import {NgxSpinnerService} from 'ngx-spinner';
declare var $: any;

@Component({
  selector: 'app-leave',
  templateUrl: './leave.component.html',
  styleUrls: ['./leave.component.css']
})
export class LeaveComponent implements OnInit {

  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;
  employee: any;
  dtOptions: DataTables.Settings = {};
  dtTrigger: Subject<any> = new Subject();
  id: any;
  allEmp = [];
  shiftId: number;
  shift: any;
  team: any;
  shiftTeam: any;
  dtInstance: DataTables.Api;
  startDate: string;
  endDate: string;
  noOfDays: string;
  remark: string;
  fkLeaveCategory: string;
  leaveCategories: any;
  // DROPDOWN
  dropdownList = [];
  selectedItems = [];
  dropdownSettings = {};

  // tslint:disable-next-line:max-line-length
  constructor(private renderer: Renderer, public http: HttpClient, private token: TokenService , public route: ActivatedRoute, private router: Router
  , private spinner: NgxSpinnerService) { }

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
    this.getCategory();



    this.fkLeaveCategory = '';
    this.startDate = '';
    this.endDate = '';
    this.noOfDays = '';
    this.remark = '';
    this.allEmp = [];

  }

  getCategory() {
    const token = this.token.get();

    this.http.get(Constants.API_URL + 'leave/getLeaveCategory' + '?token=' + token).subscribe(data => {
        this.leaveCategories = data;
      },
      error => {
        console.log(error);
      }
    );
  }

  onItemSelect(value) {
    // console.log(value);
  }
  onSelectAll(value) {
    // console.log(value);
  }

  getData() {
    const token = this.token.get();
    this.dtOptions = {
      ajax: {
        url: Constants.API_URL + 'employee/get' + '?token=' + token,
        type: 'POST',
        data: function (d: any) {


        },
      },
      columns: [
        {
          'data': function (data: any, type: any, full: any) {
            return '<input type="checkbox" class="chk form-control" name="selected_rows[]" value="' + data.empid + '">';
          },
          'orderable': false, 'searchable': false, 'name': 'selected_rows'
        },
        { data: 'firstName' , name: 'employeeinfo.firstName'},
        { data: 'EmployeeId' , name: 'employeeinfo.EmployeeId' },

        { data: 'weekend', name: 'employeeinfo.weekend'},

        {
          'data': function (data: any, type: any, full: any) {
            return ' <button class="btn btn-info" data-emp-id="' + data.empid + '"> View</button>';
          },
          'orderable': false, 'searchable': false, 'name': 'selected_rows'
        }



      ],
      processing: true,
      serverSide: true,
      pagingType: 'full_numbers',
      pageLength: 10
    };
  }

  ngAfterViewInit(): void {
    this.dtTrigger.next();

    this.renderer.listenGlobal('document', 'click', (event) => {

      if (event.target.hasAttribute('data-emp-id')) {

        const id = event.target.getAttribute('data-emp-id');
        this.showLeaveOfEmp(id);
      }


    });


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

      const that = this;
      $('.chk:checked').each(function () {
        that.allEmp.push($(this).val());
      });
      // console.log(this.allEmp);
    } else {

      $(':checkbox:checked').prop('checked', false);
    }


  }
  showLeaveOfEmp(id) {

    this.router.navigate(['leave/summery/' + id]);
    return false;

  }


  selectCategory(value) {
    this.fkLeaveCategory = value;

  }

  assignLeave() {
    if (!this.checkForm()) {
      return false;
    }

    const form = {
      allEmp: this.allEmp,
      startDate: new Date(this.startDate).toLocaleDateString(),
      endDate: new Date(this.endDate).toLocaleDateString(),
      noOfDays: this.noOfDays,
      remark: this.remark,
      fkLeaveCategory: this.fkLeaveCategory,

    };

    this.allEmp = [];
    const that = this;
    $('.chk:checked').each(function () {
      that.allEmp.push($(this).val());
    });

   // console.log(this.allEmp);

    const token = this.token.get();
    this.spinner.show();
    this.http.post(Constants.API_URL + 'leave/assignLeave' + '?token=' + token, form).subscribe(data => {

        this.spinner.hide();
        // console.log(data);
        this.ngOnInit();
        this.rerender();
        $.alert({
          title: 'Success!',
          type: 'Green',
          content: 'Leave Assigned',
          buttons: {
            tryAgain: {
              text: 'Ok',
              btnClass: 'btn-red',
              action: function () {
              }
            }
          }
        });
      },
      error => {
        console.log(error);
      }
    );




  }

  checkForm() {
    let message = '';
    let condition = true;
    this.allEmp = [];
    const that = this;
    $('.chk:checked').each(function () {
      that.allEmp.push($(this).val());
    });

    if (this.allEmp.length <= 0) {
      condition = false;
      message = 'Please Select at least one employee';
    }
    if (this.allEmp.length > 1) {
      condition = false;
      message = 'Please Select only one employee';
    }

    if (this.startDate == '') {
      condition = false;
      message = 'Please Insert Start Date';

    }

    if (this.endDate == '') {
      condition = false;
      message = 'Please Insert End Date';
    }
    if (this.endDate < this.startDate) {
      condition = false;
      message = 'End Date must be greater then Start Date';
    }
    if (this.noOfDays == '') {
      condition = false;
      message = 'Please Insert No Of Days';
    }
    if (this.fkLeaveCategory == '') {
      condition = false;
      message = 'Please Select Leave Category';
    }

    if (this.allEmp.length == 0) {
      condition = false;
      message = 'Please Select Employee';
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


  rerender() {
    this.dtElement.dtInstance.then((dtInstance: DataTables.Api) => {

      dtInstance.destroy();

      this.dtTrigger.next();
    });
  }

}
