import {Component, OnInit, AfterViewInit, Renderer, OnDestroy, ViewChild} from '@angular/core';
import {Constants} from '../../../../constants';
import {HttpClient} from '@angular/common/http';
import {TokenService} from '../../../../services/token.service';
import {Subject} from 'rxjs';
import {ActivatedRoute, Router} from '@angular/router';
import {DataTableDirective} from 'angular-datatables';
import {NgbModal} from '@ng-bootstrap/ng-bootstrap';
import {NgxPermissionsService} from 'ngx-permissions';
declare var $: any;

@Component({
  selector: 'app-show-swap',
  templateUrl: './show-swap.component.html',
  styleUrls: ['./show-swap.component.css']
})
export class ShowSwapComponent implements AfterViewInit, OnDestroy, OnInit {


  @ViewChild(DataTableDirective)


  dtElement: DataTableDirective;
  dtOptions: DataTables.Settings = {};
  dtTrigger: Subject<any> = new Subject();
  dtInstance: DataTables.Api;




  constructor(private permissionsService: NgxPermissionsService, private modalService: NgbModal, private renderer: Renderer, public http: HttpClient, private token: TokenService , public route: ActivatedRoute, private router: Router) { }

  ngOnInit() {
    this.getSwapData();


  }
  getSwapData() {

    const that = this;

    const token = this.token.get();
    this.dtOptions = {
      stateSave: true,

      'drawCallback': function () {
        const api = this.api();


      },
      ajax: {
        url: Constants.API_URL + 'swap/getAllSwapReq' + '?token=' + token,
        type: 'POST',
        data: function (d) {



        },
      },
      columns: [

        { data: 'empFullnameBy' , name: 'empFullnameBy'},
        { data: 'swap_by_date' , name: 'swap_by_date'},
        { data: 'shift_byName' , name: 'shift_byName'},
        { data: 'empFullnameFor' , name: 'empFullnameFor'},
        { data: 'swap_for_date' , name: 'swap_for_date'},
        { data: 'shift_forName' , name: 'shift_forName'},

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

      if (event.target.hasAttribute('data-edit-id')) {

        const id = event.target.getAttribute('data-edit-id');

       // this.editRequestSwap(id);


      } else if (event.target.hasAttribute('data-Accept-id')) {

        const id = event.target.getAttribute('data-Accept-id');



        this.acceptSwapReq(id);

      } else if (event.target.hasAttribute('data-Reject-id')) {

        const id = event.target.getAttribute('data-Reject-id');



        this.rejectSwapReq(id);

      }


    });


  }
  rerender() {
    this.dtElement.dtInstance.then((dtInstance: DataTables.Api) => {

      dtInstance.destroy();
      this.dtTrigger.next();

    });
  }
  ngOnDestroy(): void {
    // Do not forget to unsubscribe the event
    this.dtTrigger.unsubscribe();
  }

  acceptSwapReq(id) {


    const token = this.token.get();

    this.http.post(Constants.API_URL + 'swap/acceptSwapReq' + '?token=' + token, {'id': id}).subscribe(data1 => {


        $.alert({
          title: 'Msg',
          content: data1,
        });



        this.rerender();

    //  console.log(data1);





      },
      error => {
        console.log(error);
      }
    );



  }
  rejectSwapReq(id) {


    const token = this.token.get();

    this.http.post(Constants.API_URL + 'swap/rejectSwapReq' + '?token=' + token, {'id': id}).subscribe(data1 => {


        // $.alert({
        //   title: 'Msg',
        //   content: data1,
        // });

        console.log(data1);


        this.rerender();


      },
      error => {
        console.log(error);
      }
    );



  }







}
