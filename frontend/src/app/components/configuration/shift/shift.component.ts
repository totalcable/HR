import { Component, OnInit } from '@angular/core';
import {Constants} from "../../../constants";
import {TokenService} from "../../../services/token.service";
import {HttpClient} from "@angular/common/http";
import {Subject} from "rxjs";
import {NgbModal, ModalDismissReasons} from '@ng-bootstrap/ng-bootstrap';
declare var $ :any;

@Component({
  selector: 'app-shift',
  templateUrl: './shift.component.html',
  styleUrls: ['./shift.component.css']
})
export class ShiftComponent implements OnInit {

  shifts:any;
  modalRef:any;

  shiftObj:any={
    shiftId:"",
    shiftName:"",
    inTime:"",
    outTime:"",
    fkDepartmentId:""
  };
  checkTable=0;
  dropdownSettings2 = {};
  departments:any;
  selectedDropDown = [];

  dtOptions:DataTables.Settings={};
  dtTeigger:Subject<any>=new Subject();
  constructor(private modalService: NgbModal,private http:HttpClient,private token:TokenService) { }

  ngOnInit() {
    this.getShift();
    this.getAllDepartment();

    this.dropdownSettings2 = {
      singleSelection: true,
      idField:'id',
      textField:'departmentName',
      selectAllText: 'Select All',
      unSelectAllText: 'UnSelect All',
      // itemsShowLimit: 3,
      allowSearchFilter: true,
      closeDropDownOnSelection:true,
    };

  }
  getAllDepartment(){

    const token=this.token.get();


    this.http.get(Constants.API_URL+'department/get'+'?token='+token).subscribe(data => {

        this.departments=data;

      },
      error => {
        console.log(error);
      }
    );

  }

  getShift(){
    const token=this.token.get();
    this.http.get(Constants.API_URL+'shift/get'+'?token='+token).subscribe(data => {
        // console.log(data);
        this.shifts=data;
        if(this.checkTable==0){
          this.dtTeigger.next();
          this.checkTable++;
        }


      },
      error => {
        console.log(error);
      }
    );
  }

  openLg(content) {
    this.shiftObj={};
    this.modalRef =  this.modalService.open(content, { size: 'lg',backdrop:'static'});

  }

  onSubmit() {

    if (!this.checkForm()) {
      return false;
    } else {


      const token = this.token.get();

      this.http.post(Constants.API_URL + 'shift/post' + '?token=' + token, this.shiftObj).subscribe(data => {
          // console.log(data);
          $.alert({
            title: data,
            content: 'Update Successfull',
          });
          this.getShift();

        },
        error => {
          console.log(error);
        }
      );

      this.modalRef.close();
    }
  }

  checkForm() //validation
  {
    let message="";
    let condition=true;

    if(this.selectedDropDown.length == 0){

      condition=false;
      message="Please Select a Department";


    }
    if (this.shiftObj.inTime == "" || this.shiftObj.inTime ==null ){

      condition=false;
      message="Please insert inTime";

    }
    if (this.shiftObj.outTime == "" || this.shiftObj.outTime == null ){

      condition=false;
      message="Please insert outTime";

    }
    if (this.shiftObj.shiftName == "" || this.shiftObj.shiftName == null ){

      condition=false;
      message="Please insert ShiftName";

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

  edit(id,content){

    let i=0;
    for(i;i<this.shifts.length;i++){
      if(this.shifts[i].shiftId==id){
        this.shiftObj.inTime=this.shifts[i].inTime;
        this.shiftObj.outTime=this.shifts[i].outTime;
        this.shiftObj.shiftName=this.shifts[i].shiftName;
        this.shiftObj.shiftId=this.shifts[i].shiftId;
        this.shiftObj.fkDepartmentId=this.shifts[i].fkDepartmentId;

        let T={
          'id':this.shifts[i].deptId,
          'departmentName':this.shifts[i].departmentName,
        }


        this.selectedDropDown.push(T);
        break;
      }
    }
    this.modalRef =  this.modalService.open(content, { size: 'lg',backdrop:'static'});
    console.log(this.selectedDropDown);

  }
  onItemSelectDepartment(deptId){

    this.shiftObj.fkDepartmentId=this.selectedDropDown[0]['id'];
    console.log(this.shiftObj.fkDepartmentId);

  }
  onItemDeSelectDepartment(deptId){

    this.shiftObj.fkDepartmentId="";
    this.selectedDropDown=[];
    console.log(this.shiftObj.fkDepartmentId);

  }


}
