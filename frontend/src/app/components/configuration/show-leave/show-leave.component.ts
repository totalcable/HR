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
  selector: 'app-show-leave',
  templateUrl: './show-leave.component.html',
  styleUrls: ['./show-leave.component.css']
})
export class ShowLeaveComponent implements  AfterViewInit, OnDestroy, OnInit {

  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;
  employee: any = {};
  dtOptions: DataTables.Settings = {};
  dtTrigger: Subject<any> = new Subject();
  id: any;
  allEmp = [];
  shiftId: number;
  shift: any;
  dtInstance: DataTables.Api;
  startDate: string;
  // DROPDOWN
  dropdownList = [];
  selectedItems = [];
  dropdownSettings = {};
  modalRef: any;
  rejectModel: any = {};
  leaveCategories: any;

  constructor(private modalService: NgbModal, private renderer: Renderer, public http: HttpClient, private token: TokenService , public route: ActivatedRoute, private router: Router) { }

  ngOnInit() {

    this.getData();
    this.getCategory();

  }

  getCategory() {
    this.employee.fkLeaveCategory = '';

    const token = this.token.get();
    this.http.get(Constants.API_URL + 'leave/getLeaveCategory' + '?token=' + token).subscribe(data => {
        this.leaveCategories = data;
      },
      error => {
        console.log(error);
      }
    );
  }

  getData() {
    const token = this.token.get();
    this.dtOptions = {
      stateSave: true,
      'createdRow': function( row, data, dataIndex) {
        // if ( data['applicationStatus'] ==  'Pending') {
        //   $('td', row).css('background-color', '#FC7153');
        //   $('td', row).css('color', 'white');
        // }
        // if ( data['applicationStatus'] ==  'Rejected') {
        //   // $('td', row).css('background-color', '#FC7153');
        //   $('td', row).css('color', 'red');
        // }

        if (data['departmentHeadApproval'] == null) {
          $('td', row).css('background-color', '#FC7153');
          $('td', row).css('color', 'white');
        }if (data['departmentHeadApproval'] == 0) {
          return 'Rejected by Department Head';
        } else if (data['departmentHeadApproval'] != null && data['departmentHeadApproval'] != 0) {
          if (data['HR_adminApproval'] == null) {

            $('td', row).css('background-color', '#FC7153');
            $('td', row).css('color', 'white');

          } else if (data['HR_adminApproval'] != null && data['HR_adminApproval'] != 0) {
            return 'Approved';
          } else if (data['HR_adminApproval'] == 0) {
            $('td', row).css('color', 'red');
          }
        }

      },
      'drawCallback': function () {
        const api = this.api();

        // $( api.table().footer() ).html(
        //
        //     // $('#footTotal').html(api.column( 4, {page:'current'} ).data().sum())
        //     console.log(api.column( 4, {page:'current'} ).data().sum())
        // );
        // console.log(api.column( 4, {page:'current'} ).data().sum());

        // $( api.table().footer() ).html(
        //
        //     $('#footTotal').html(api.column( 4, {page:'current'} ).data().sum())
        // );

      },
      ajax: {
        url: Constants.API_URL + 'leave/getLeaveRequests' + '?token=' + token,
        type: 'POST',
        data: function (d) {

        },
      },
      columns: [

        { data: 'firstName' , name: 'employeeinfo.firstName'},
        { data: 'lastName' , name: 'employeeinfo.lastName'},
        { data: 'startDate' , name: 'hrmleaves.startDate'},
        { data: 'endDate' , name: 'hrmleaves.endDate'},
        { data: 'noOfDays' , name: 'hrmleaves.noOfDays'},
        { data: 'categoryName' , name: 'hrmleavecategories.categoryName'},
        { data: 'remarks' , name: 'hrmleaves.remarks'},
        { data: 'rejectCause' , name: 'hrmleaves.rejectCause'},
        // { data: 'applicationStatus' , name: 'hrmleaves.applicationStatus'},

        {

          'data': function (data: any, type: any, full: any) {
            if (data.departmentHeadApproval == null) {
              return 'Department Head Approval Pending';
            }if (data.departmentHeadApproval == 0) {
              return 'Rejected by Department Head';
            } else if (data.departmentHeadApproval != null && data.departmentHeadApproval != 0) {
              if (data.HR_adminApproval == null) {

                return 'HR Approval Pending';

              } else if (data.HR_adminApproval != null && data.HR_adminApproval != 0) {
                return 'Approved';
              } else if (data.HR_adminApproval == 0) {
                return 'Rejected by Hr/Admin';
              }
            }
          },
          'orderable': false, 'searchable': false, 'name': 'selected_rows'
        },

        // {
        //
        //   'data': function (data: any, type: any, full: any) {
        //     return '<div class="dropdown">\n' +
        //       '  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">\n' +
        //       '  </button>\n' +
        //       '  <div class="dropdown-menu">\n' +
        //       '    <button class="dropdown-item" data-approve-id="' + data.id + '">Approve</button>\n' +
        //       '    <button class="dropdown-item" data-reject-id="' + data.id + '">Reject</button>\n' +
        //       '    <button class="dropdown-item" data-edit-id="' + data.id + '" >Edit</button>\n' +
        //       '  </div>\n' +
        //       '</div>';
        //   },
        //   'orderable': false, 'searchable': false, 'name': 'selected_rows'
        // }

        {

          'data': function (data: any, type: any, full: any) {


            if (data.userDesignationTitle == Constants.manager && data.departmentHeadApproval == null) {

              return '<div class="dropdown">\n' +
                '  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">\n' +
                '  </button>\n' +
                '  <div class="dropdown-menu">\n' +
                '    <button class="dropdown-item" data-edit-id="' + data.id + '" >Edit</button>\n' +
                '    <button class="dropdown-item" data-Accept-id="' + data.id + '" >Accept</button>\n' +
                '    <button class="dropdown-item" data-Reject-id="' + data.id + '" >Reject</button>\n' +

                '  </div>\n' +
                '</div>';

            } else if (data.userDesignationTitle == Constants.manager && data.departmentHeadApproval != null && data.departmentHeadApproval != 0) {

              return '<div class="dropdown">\n' +
                '  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">\n' +
                '  </button>\n' +
                '  <div class="dropdown-menu">\n' +
                '    <button class="dropdown-item" data-edit-id="' + data.id + '" >Edit</button>\n' +

                '    <button class="dropdown-item" data-Reject-id="' + data.id + '" >Reject</button>\n' +

                '  </div>\n' +
                '</div>';

            } else if (data.userDesignationTitle == Constants.manager && data.departmentHeadApproval == 0) {

              return '<div class="dropdown">\n' +
                '  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">\n' +
                '  </button>\n' +
                '  <div class="dropdown-menu">\n' +
                '    <button class="dropdown-item" data-edit-id="' + data.id + '" >Edit</button>\n' +

                '    <button class="dropdown-item" data-Accept-id="' + data.id + '" >Accept</button>\n' +

                '  </div>\n' +
                '</div>';

            } else if (data.userDesignationTitle == Constants.HR && data.HR_adminApproval == null && data.departmentHeadApproval != null && data.departmentHeadApproval != 0) {

              return '<div class="dropdown">\n' +
                '  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">\n' +
                '  </button>\n' +
                '  <div class="dropdown-menu">\n' +
                '    <button class="dropdown-item" data-edit-id="' + data.id + '" >Edit</button>\n' +
                '    <button class="dropdown-item" data-Accept-id="' + data.id + '" >Accept</button>\n' +
                '    <button class="dropdown-item" data-Reject-id="' + data.id + '" >Reject</button>\n' +

                '  </div>\n' +
                '</div>';

            } else if (data.userDesignationTitle == Constants.HR && data.HR_adminApproval != null && data.HR_adminApproval != 0 && data.departmentHeadApproval != null && data.departmentHeadApproval != 0) {

              return '<div class="dropdown">\n' +
                '  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">\n' +
                '  </button>\n' +
                '  <div class="dropdown-menu">\n' +
                '    <button class="dropdown-item" data-edit-id="' + data.id + '" >Edit</button>\n' +

                '    <button class="dropdown-item" data-Reject-id="' + data.id + '" >Reject</button>\n' +

                '  </div>\n' +
                '</div>';

            } else if (data.userDesignationTitle == Constants.HR && data.HR_adminApproval == 0 && data.departmentHeadApproval != null && data.departmentHeadApproval != 0) {

              return '<div class="dropdown">\n' +
                '  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">\n' +
                '  </button>\n' +
                '  <div class="dropdown-menu">\n' +
                '    <button class="dropdown-item" data-edit-id="' + data.id + '" >Edit</button>\n' +

                '    <button class="dropdown-item" data-Accept-id="' + data.id + '" >Accept</button>\n' +

                '  </div>\n' +
                '</div>';

            } else {
              return '';
            }

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
      // this.approved();
      // if (event.target.hasAttribute('data-approve-id')) {
      //
      //   const id = event.target.getAttribute('data-approve-id');
      //   this.changeStatus(id, 'Approved');
      //
      //
      // } else if (event.target.hasAttribute('data-reject-id')) {
      //
      //   const id = event.target.getAttribute('data-reject-id');
      //   // this.changeStatus(id,'Rejected');
      //   this.reject(id);
      // }
      if (event.target.hasAttribute('data-edit-id')) {

        const id = event.target.getAttribute('data-edit-id');
        this.edit(id);
      } else if (event.target.hasAttribute('data-Accept-id')) {

        const id = event.target.getAttribute('data-Accept-id');



        this.changeStatus(id, 'Approved');

      } else if (event.target.hasAttribute('data-Reject-id')) {

        const id = event.target.getAttribute('data-Reject-id');



        this.reject(id);

      }


    });
  }
  rerender() {
    this.dtElement.dtInstance.then((dtInstance: DataTables.Api) => {

      dtInstance.destroy();

      this.dtTrigger.next();
    });
  }

  openLg(content) {
    // this.shiftObj={};
    this.modalRef =  this.modalService.open(content, { size: 'lg'});

  }
  ngOnDestroy(): void {
    // Do not forget to unsubscribe the event
    this.dtTrigger.unsubscribe();
  }
  edit(id) {

    const token = this.token.get();
    this.http.post(Constants.API_URL + 'leave/get/individual' + '?token=' + token, {id: id}).subscribe(data => {
        // console.log(data);
        this.employee = data;
        $('#myModal').modal();
      },
      error => {
        console.log(error);
      }
    );


  }
  changeStatus(id, status) {
    const token = this.token.get();
    this.http.post(Constants.API_URL + 'leave/change/status' + '?token=' + token, {id: id, applicationStatus: status}).subscribe(data => {
        this.rerender();
        $.alert({
          title: 'Msg',
          content: data,
        });
      },
      error => {
        console.log(error);
      }
    );
  }
  reject(id) {
    // alert(id);
    const token = this.token.get();
    this.http.post(Constants.API_URL + 'leave/get/individual' + '?token=' + token, {id: id}).subscribe(data => {
        console.log(data);
        // this.employee=data;
        this.rejectModel = data;
        $('#rejectModal').modal();
      },
      error => {
        console.log(error);
      }
    );
  }

  updateReject() {
    // console.log(this.token.getUserLocal().id);

    const form = {
      id: this.rejectModel.id,
      startDate: new Date(this.rejectModel.startDate).toLocaleDateString(),
      endDate: new Date(this.rejectModel.endDate).toLocaleDateString(),
      noOfDays: this.rejectModel.noOfDays,
      remark: this.rejectModel.remark,
      fkLeaveCategory: this.rejectModel.fkLeaveCategory,
      status: 'Rejected',
      rejectCause: this.rejectModel.rejectCause,
      rejectBy: this.token.getUserLocal().id


    };


    const token = this.token.get();

    this.http.post(Constants.API_URL + 'leave/individual/update' + '?token=' + token, form).subscribe(data => {
        // console.log(data);
        $('#rejectModal').modal('hide');
        this.rerender();

        $.alert({
          title: 'Msg',
          type: 'Green',
          content: data,
        });


        // $.alert({
        //   title: 'Success!',
        //   type: 'Green',
        //   content: 'Leave Rejected',
        //   buttons: {
        //     tryAgain: {
        //       text: 'Ok',
        //       btnClass: 'btn-red',
        //       action: function () {
        //       }
        //     }
        //   }
        // });
      },
      error => {
        console.log(error);
      }
    );
  }
  updateLeave() {


    const form = {
      id: this.employee.id,
      startDate: new Date(this.employee.startDate).toLocaleDateString(),
      endDate: new Date(this.employee.endDate).toLocaleDateString(),
      noOfDays: this.employee.noOfDays,
      remark: this.employee.remark,
      fkLeaveCategory: this.employee.fkLeaveCategory,

    };


    const token = this.token.get();

    this.http.post(Constants.API_URL + 'leave/individual/update' + '?token=' + token, form).subscribe(data => {
        console.log(data);

        $('#myModal').modal('hide');
        this.rerender();


        $.alert({
          title: 'Success!',
          type: 'Green',
          content: 'Leave Updated',
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

}
