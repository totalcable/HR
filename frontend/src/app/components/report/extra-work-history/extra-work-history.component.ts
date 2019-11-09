import {Component, OnInit, AfterViewInit, Renderer, OnDestroy, ViewChild} from '@angular/core';
import {Constants} from "../../../constants";
import {HttpClient} from "@angular/common/http";
import {TokenService} from "../../../services/token.service";
import {Subject} from "rxjs";
import {ActivatedRoute, Router} from "@angular/router";
import {DataTableDirective} from "angular-datatables";
import {NgbModal} from "@ng-bootstrap/ng-bootstrap";
declare var $ :any;

@Component({
  selector: 'app-extra-work-history',
  templateUrl: './extra-work-history.component.html',
  styleUrls: ['./extra-work-history.component.css']
})
export class ExtraWorkHistoryComponent implements   AfterViewInit,OnDestroy,OnInit {

  @ViewChild(DataTableDirective)

  dtElement: DataTableDirective;
  dtOptions:DataTables.Settings={};
  dtTrigger:Subject<any>=new Subject();
  dtInstance:DataTables.Api;


  constructor(private modalService: NgbModal,private renderer: Renderer,public http: HttpClient, private token:TokenService , public route:ActivatedRoute, private router: Router) {

  }

  ngOnInit() {
  }

  getAllGovtHoliday()
  {

    const token=this.token.get();
    this.dtOptions = {
      stateSave:true,

      "drawCallback": function () {
        let api = this.api();


      },
      ajax: {
        url: Constants.API_URL+'extraWorkHistory/getAll'+'?token='+token,
        type: 'POST',
        data:function (d){

          if ($('#startDate').val()!='')
          {
            d['startDate']=$('#startDate').val();

          }
          if ($('#endDate').val()!='')
          {
            d['endDate']=$('#endDate').val();

          }
          if ($('#ExtraWorkStatus').val()!='')
          {
            d['ExtraWorkStatus']=$('#ExtraWorkStatus').val();

          }


        },
      },
      columns: [

        { data: 'empFullname' ,name:'empFullname'},

        { data: 'startDate' ,name:'startDate'},
        { data: 'endDate' ,name:'endDate'},
        { data: 'noOfDays' ,name:'noOfDays'},

        { data: 'status' ,name:'status'},



        {

          "data": function (data: any, type: any, full: any) {
            return '<div class="dropdown">\n' +
              '  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">\n' +
              '  </button>\n' +
              '  <div class="dropdown-menu">\n' +
              '    <button class="dropdown-item" data-edit-id="'+data.id+'" >Edit</button>\n' +
              '  </div>\n' +
              '</div>';
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

  calculateAdjustment(){

    if(!this.checkForm()){
      return false;
    }else {

      let form={

        startDate:$('#startDate').val(),
       endDate:$('#endDate').val(),


      };

      const token=this.token.get();

      this.http.post(Constants.API_URL+'ExtraWork/calculateextraWork'+'?token='+token,form).subscribe(data => {

        console.log(data);


          // $.alert({
          //   title: 'Success!',
          //   type: 'Green',
          //   content: 'Adjustment updated',
          //   buttons: {
          //     tryAgain: {
          //       text: 'Ok',
          //       btnClass: 'btn-red',
          //       action: function () {
          //
          //       }
          //     }
          //   }
          // });
          // this.rerender();

        },
        error => {
          console.log(error);
        }
      );

    }

  }
  checkForm()
  {

    let message="";
    let condition=true;

    let startDate=$('#startDate').val();
    let endDate=$('#endDate').val();
    if (startDate === 'undefined' || startDate =='')
    {

        condition=false;
        message="Please Select Start Date";
    }
    if (endDate === 'undefined' || endDate =='')
    {

        condition=false;
        message="Please Select End Date";
    }

    if (condition==false){
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

}
