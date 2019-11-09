import {Component, OnInit, AfterViewInit, Renderer, OnDestroy, ViewChild} from '@angular/core';
import {Constants} from '../../../constants';
import {HttpClient} from '@angular/common/http';
import {TokenService} from '../../../services/token.service';
import {Subject} from 'rxjs';
import {ActivatedRoute, Router} from '@angular/router';
import {DataTableDirective} from 'angular-datatables';
import {NgbModal} from '@ng-bootstrap/ng-bootstrap';
declare var $: any;
import { NgxPermissionsService } from 'ngx-permissions';

@Component({
  selector: 'app-govment-holiday',
  templateUrl: './govment-holiday.component.html',
  styleUrls: ['./govment-holiday.component.css']
})
export class GovmentHolidayComponent implements  AfterViewInit, OnDestroy, OnInit {



  @ViewChild('editGovtHoliday') editModal: any;

  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;
  dtInstance: DataTables.Api;
  dtTrigger: Subject<any> = new Subject();
  dtOptions: DataTables.Settings = {};

  modalRef: any;
  rejectModel: any = {};
  govtHolidayObj: any = {
    holidayName: '',
    startDate: '',
    endDate: '',
    purpose: '',
    noOfDays: '',
    createdBy: '',
    status: '',
    modified_date: '',
    modified_by: ''

  };
  editShow: boolean;



  constructor(private modalService: NgbModal, private renderer: Renderer, public http: HttpClient, private token: TokenService ,
              public route: ActivatedRoute, private router: Router, private permissionsService: NgxPermissionsService, ) { }

  ngOnInit() {

      this.getAllGovtHoliday();

      if (localStorage.getItem('role') == 'admin') {
        this.editShow = true;
      } else {
        this.editShow = false;
      }

    // if (this.permissionsService.hasPermission('admin')) {
    //   this.editShow = true;
    //   console.log(this.permissionsService.getPermissions());
    // } else {
    //   this.editShow = false;
    // }
   // console.log(localStorage.getItem('designation'));


  }

   getAllGovtHoliday() {

     if (localStorage.getItem('role') == 'admin') {

      const token = this.token.get();
      this.dtOptions = {
        stateSave: true,

        'drawCallback': function () {
          const api = this.api();


        },
        ajax: {
          url: Constants.API_URL + 'govtHoliday/getAllGovtHoliday' + '?token=' + token,
          type: 'POST',
          data: function (d) {

            if ($('#startDate').val() != '') {
              d['startDate'] = $('#startDate').val();

            }
            if ($('#endDate').val() != '') {
              d['endDate'] = $('#endDate').val();

            }
            if ($('#HolidayStatus').val() != '') {
              d['HolidayStatus'] = $('#HolidayStatus').val();

            }

          },
        },
        columns: [

          { data: 'holidayName' , name: 'holidayName'},
          { data: 'startDate' , name: 'startDate'},
          { data: 'endDate' , name: 'endDate'},
          { data: 'noOfDays' , name: 'noOfDays'},
          { data: 'purpose' , name: 'purpose'},
          { data: 'status' , name: 'status'},

          { data: 'empFullname' , name: 'empFullname'},

          {


            'data': function (data: any, type: any, full: any) {
              return '<div class="dropdown">\n' +
                '  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">\n' +
                '  </button>\n' +
                '  <div class="dropdown-menu">\n' +
                '    <button class="dropdown-item" data-edit-id="' + data.id + '" >Edit</button>\n' +
                '  </div>\n' +
                '</div>';
            },
            'orderable': false, 'searchable': false, 'name': 'selected_rows'
          }


        ],
        processing: true,
        serverSide: true,
        pagingType: 'full_numbers',
        pageLength: 10
      };

    } else {

      const token = this.token.get();
      this.dtOptions = {
        stateSave: true,

        'drawCallback': function () {
          const api = this.api();


        },
        ajax: {
          url: Constants.API_URL + 'govtHoliday/getAllGovtHoliday' + '?token=' + token,
          type: 'POST',
          data: function (d) {

            if ($('#startDate').val() != '') {
              d['startDate'] = $('#startDate').val();

            }
            if ($('#endDate').val() != '') {
              d['endDate'] = $('#endDate').val();

            }
            if ($('#HolidayStatus').val() != '') {
              d['HolidayStatus'] = $('#HolidayStatus').val();

            }

          },
        },
        columns: [

          { data: 'holidayName' , name: 'holidayName'},
          { data: 'startDate' , name: 'startDate'},
          { data: 'endDate' , name: 'endDate'},
          { data: 'noOfDays' , name: 'noOfDays'},
          { data: 'purpose' , name: 'purpose'},
          { data: 'status' , name: 'status'},

          { data: 'empFullname' , name: 'empFullname'},




        ],
        processing: true,
        serverSide: true,
        pagingType: 'full_numbers',
        pageLength: 10
      };

    }





  }

  ngAfterViewInit(): void {

    this.dtTrigger.next();

    this.renderer.listenGlobal('document', 'click', (event) => {

      if (event.target.hasAttribute('data-edit-id')) {

        const id = event.target.getAttribute('data-edit-id');

        this.editGovtHoliday(id);


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
  addModal(addGovtHoliday) {

    this.govtHolidayObj = {};
    this.modalRef =  this.modalService.open(addGovtHoliday, { size: 'lg', backdrop: 'static'});
  }
  insertGovtHoliday() {
    const token = this.token.get();

    this.http.post(Constants.API_URL + 'govtHoliday/insertNewGovtHoliday' + '?token=' + token, this.govtHolidayObj).subscribe(data => {

        $.alert({
          title: data,
          content: 'Update Successfull',
        });
        this.rerender();
        this.govtHolidayObj = {};

      },
      error => {
        console.log(error);
      }
    );

    this.modalRef.close();



  }

  editGovtHoliday(id) {

    const token = this.token.get();

    this.http.post(Constants.API_URL + 'govtHoliday/getGovtHolidayInfo' + '?token=' + token, {id: id}).subscribe(data => {

        this.govtHolidayObj.holidayName = data['holidayName'];
        this.govtHolidayObj.startDate = data['startDate'];
        this.govtHolidayObj.endDate = data['endDate'];
        this.govtHolidayObj.purpose = data['purpose'];
        this.govtHolidayObj.noOfDays = data['noOfDays'];
        this.govtHolidayObj.status = data['status'];
        this.govtHolidayObj.id = data['id'];

        console.log(this.govtHolidayObj);

        this.modalRef = this.modalService.open(this.editModal, {size: 'lg', backdrop: 'static'});

      },
      error => {
        console.log(error);
      }
    );


  }
  updateGovtHoliday() {

    const token = this.token.get();

    this.http.post(Constants.API_URL + 'govtHoliday/updateGovtHoliday' + '?token=' + token, this.govtHolidayObj).subscribe(data => {

        $.alert({
          title: data,
          content: 'Update Successfull',
        });
        this.rerender();
        this.govtHolidayObj = {};

      },
      error => {
        console.log(error);
      }
    );

    this.modalRef.close();

  }

}
