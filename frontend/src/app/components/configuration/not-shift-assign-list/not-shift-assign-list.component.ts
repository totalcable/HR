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
  selector: 'app-not-shift-assign-list',
  templateUrl: './not-shift-assign-list.component.html',
  styleUrls: ['./not-shift-assign-list.component.css']
})
export class NotShiftAssignListComponent implements OnInit {

  startDate:string;
  endDate:string;
  empList:any;
  assignedLog:any;
  newS:any;
  newE:any;

  constructor(private modalService: NgbModal,private renderer: Renderer,public http: HttpClient, private token:TokenService , public route:ActivatedRoute, private router: Router) {

  }

  ngOnInit() {


  }

  search(){

    if(this.startDate ==null || this.endDate ==null){
      alert("Empty");
    }else {

          let form={

            startDate:new Date(this.startDate).toLocaleDateString(),
            endDate:new Date(this.endDate).toLocaleDateString(),

          };
          const token=this.token.get();

          this.http.post(Constants.API_URL+'dateRanges/NotAssignedShift'+'?token='+token,form).subscribe(data1 => {

              this.empList=data1;

              this.newS=encodeURI(new Date(this.startDate).toLocaleDateString());
              this.newE=encodeURI(new Date(this.endDate).toLocaleDateString());
              console.log(this.newE);


            },
            error => {
              console.log(error);
            }
          );

    }

  }
  seeNotAssignedShift(empId){

    let form={
      empId:empId,
      startDate:new Date(this.startDate).toLocaleDateString(),
      endDate:new Date(this.endDate).toLocaleDateString(),

    };
    const token=this.token.get();

    this.http.post(Constants.API_URL+'dateRanges/NotAssignedShiftPerEmp'+'?token='+token,form).subscribe(data1 => {

        this.assignedLog=data1;
        console.log(data1);

      },
      error => {
        console.log(error);
      }
    );

  }

}
