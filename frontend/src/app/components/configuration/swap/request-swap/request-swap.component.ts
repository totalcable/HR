import {Component, OnInit, AfterViewInit, Renderer, OnDestroy, ViewChild} from '@angular/core';
import {Constants} from "../../../../constants";
import {HttpClient} from "@angular/common/http";
import {TokenService} from "../../../../services/token.service";
import {Subject} from "rxjs";
import {ActivatedRoute, Router} from "@angular/router";
import {DataTableDirective} from "angular-datatables";
import {NgbModal} from "@ng-bootstrap/ng-bootstrap";
declare var $ :any;

@Component({
  selector: 'app-request-swap',
  templateUrl: './request-swap.component.html',
  styleUrls: ['./request-swap.component.css']
})
export class RequestSwapComponent implements  AfterViewInit,OnDestroy,OnInit {

  @ViewChild('editrequestSwap') editModal: any;

  modalRef:any;

  dropdownSettingsShift = {};
  dropdownSettingsEmp = {};
  shiftByRequesterDepartment:any;
  empByRequesterDepartment:any;

  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;
  dtOptions:DataTables.Settings={};
  dtTrigger:Subject<any>=new Subject();
  dtInstance:DataTables.Api;

  swap:any={


    id:"",
    swap_by:"",
    swap_by_shift:[],
    swap_by_Date:"",
    swap_by_inTime:"",
    swap_by_outTime:"",
    swap_for:[],
    swap_for_date:"",
    swap_for_shift:[],
    swap_for_inTime:"",
    swap_for_outTime:"",
    departmentHeadApproval:"",
    HR_adminApproval:"",


  };


  constructor(private modalService: NgbModal,private renderer: Renderer,public http: HttpClient, private token:TokenService , public route:ActivatedRoute, private router: Router) { }

  ngOnInit() {


    this.getEmpSwapReq();
    this.getShiftByRequesterDepartment();
    this.getEmpByRequesterDepartment();


    this.dropdownSettingsShift = {
      singleSelection: true,
      idField: 'shiftId',
      textField: 'shiftName',
      // selectAllText: 'Select All',
      // unSelectAllText: 'UnSelect All',
      // itemsShowLimit: 3,
      allowSearchFilter: true,
      closeDropDownOnSelection:true,
    };
    this.dropdownSettingsEmp = {
      singleSelection: true,
      idField: 'id',
      textField: 'empFullname',
      // selectAllText: 'Select All',
      // unSelectAllText: 'UnSelect All',
      // itemsShowLimit: 3,
      allowSearchFilter: true,
      closeDropDownOnSelection:true,
    };



  }

  getShiftByRequesterDepartment(){

    const token=this.token.get();

    this.http.get(Constants.API_URL+'swap/getAllShiftByRequesterDepartment'+'?token='+token).subscribe(data => {
        this.shiftByRequesterDepartment=data;
       // console.log(data);
      },
      error => {
        console.log(error);
      }
    );

  }
  getEmpByRequesterDepartment(){

    const token=this.token.get();

    this.http.get(Constants.API_URL+'swap/getAllemployeeByRequesterDepartment'+'?token='+token).subscribe(data => {
        this.empByRequesterDepartment=data;
        console.log(data);
      },
      error => {
        console.log(error);
      }
    );

  }

  submitSwapRequest(){

   // console.log(this.swap);

    this.swap.swap_by_Date=new Date(this.swap.swap_by_Date).toLocaleDateString();
    this.swap.swap_for_date=new Date(this.swap.swap_for_date).toLocaleDateString();

    const token=this.token.get();

    this.http.post(Constants.API_URL+'swap/submitNewSwapRequestByEmp'+'?token='+token,this.swap).subscribe(data1 => {

      //console.log(data1);

        $.alert({
          title: 'Success',
          content: 'Update Successfull',
        });

        this.swap={};

        this.rerender();




      },
      error => {
        console.log(error);
      }
    );

  }
  submitEditSwapRequest(){

    console.log(this.swap);
    this.swap.swap_by_Date=new Date(this.swap.swap_by_Date).toLocaleDateString();
    this.swap.swap_for_date=new Date(this.swap.swap_for_date).toLocaleDateString();

    const token=this.token.get();

    this.http.post(Constants.API_URL+'swap/submitNewSwapRequestByEmp'+'?token='+token,this.swap).subscribe(data1 => {

        console.log(data1);

        $.alert({
          title: 'Success',
          content: 'Update Successfull',
        });

        this.swap={
          id:"",
          swap_by:"",
          swap_by_shift:[],
          swap_by_Date:"",
          swap_by_inTime:"",
          swap_by_outTime:"",
          swap_for:[],
          swap_for_date:"",
          swap_for_shift:[],
          swap_for_inTime:"",
          swap_for_outTime:"",
          departmentHeadApproval:"",
          HR_adminApproval:"",


        };

        this.rerender();
        this.modalRef.close();




      },
      error => {
        console.log(error);
      }
    );

  }
  getEmpSwapReq(){

    const token=this.token.get();
    this.dtOptions = {
      stateSave:true,

      "drawCallback": function () {
        let api = this.api();



      },

      ajax: {
        url: Constants.API_URL+'swap/getEmpSwapReq'+'?token='+token,
        type: 'POST',
        data:function (d){


        },
      },
      columns: [

        { data: 'empFullnameBy' ,name:'empFullnameBy'},
        { data: 'swap_by_date' ,name:'swap_by_date'},
        { data: 'shift_byName' ,name:'shift_byName'},
        { data: 'empFullnameFor' ,name:'empFullnameFor'},
        { data: 'swap_for_date' ,name:'swap_for_date'},
        { data: 'shift_forName' ,name:'shift_forName'},

        {

          "data": function (data: any, type: any, full: any)
          {
            if (data.departmentHeadApproval==null){
              return 'Department Head Approval Pending';
            }if (data.departmentHeadApproval==0){
              return 'Rejected by Department Head';
            }else if(data.departmentHeadApproval!=null && data.departmentHeadApproval !=0) {
              if (data.HR_adminApproval==null){

                return 'HR Approval Pending';

              }else if (data.HR_adminApproval!=null && data.HR_adminApproval !=0){
                return 'Approved';
              }else if (data.HR_adminApproval ==0){
                return 'Rejected by Hr/Admin';
              }
            }
          },
          "orderable": false, "searchable":false, "name":"selected_rows"
        },



        {

          "data": function (data: any, type: any, full: any) {

            if (data.departmentHeadApproval==0||data.departmentHeadApproval==null){

              return '<div class="dropdown">\n' +
                '  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">\n' +
                '  </button>\n' +
                '  <div class="dropdown-menu">\n' +
                '    <button class="dropdown-item" data-edit-id="'+data.id+'" >Edit</button>\n' +
                '  </div>\n' +
                '</div>';

            }else {
              return '';
            }

          },
          "orderable": false, "searchable":false, "name":"selected_rows"
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

      if (event.target.hasAttribute("data-edit-id")) {

        let id=event.target.getAttribute("data-edit-id");

        this.editRequestSwap(id);


      }



    });


  }
  rerender(){
    this.dtElement.dtInstance.then((dtInstance: DataTables.Api) => {

      dtInstance.destroy();
      this.dtTrigger.next();

    });
  }
  ngOnDestroy(): void {
    // Do not forget to unsubscribe the event
    this.dtTrigger.unsubscribe();
  }
  editRequestSwap(id){

    this.swap.swap_by_shift=[];
    this.swap.swap_for=[];
    this.swap.swap_for_shift=[];

    const token=this.token.get();

    this.http.post(Constants.API_URL+'swap/editSwapRequest'+'?token='+token,{id:id}).subscribe(data => {

        this.swap.id=data['id'];
        this.swap.swap_by=data['swap_by'];


        let B={
          'shiftId':data['swap_by_shift'],
          'shiftName':data['shift_byName'],
        };
        let F={
          'shiftId':data['swap_for_shift'],
          'shiftName':data['shift_forName'],
        };
        let E={
          'id':data['swap_for'],
          'empFullname':data['empFullnameFor'],
        };

        this.swap.swap_by_shift.push(B);
        this.swap.swap_for_shift.push(B);
        this.swap.swap_for.push(E);


        this.swap.swap_by_Date=data['swap_by_date'];
        this.swap.swap_by_inTime=data['swap_by_inTime'];
        this.swap.swap_by_outTime=data['swap_by_outTime'];

        this.swap.swap_for_date=data['swap_for_date'];

        this.swap.swap_for_inTime=data['swap_for_inTime'];
        this.swap.swap_for_outTime=data['swap_for_outTime'];

       // console.log(this.swap);

        this.modalRef = this.modalService.open(this.editModal, {size: 'lg',backdrop:'static'});

      },
      error => {
        console.log(error);
      }
    );

  }

}
