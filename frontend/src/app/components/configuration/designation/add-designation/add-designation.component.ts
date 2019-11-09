import { Component, OnInit } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Constants } from '../../../../constants';
import {Designation} from '../../../../model/designation.model';
import {TokenService} from '../../../../services/token.service';
import {Department} from '../../../../model/department.model';
declare var $: any;

@Component({
  selector: 'app-add-designation',
  templateUrl: './add-designation.component.html',
  styleUrls: ['./add-designation.component.css']
})
export class AddDesignationComponent implements OnInit {

  id: number;

  designationForm = {} as Designation;
  designation: any;


  constructor(public http: HttpClient, private token: TokenService) { }

  ngOnInit() {
    this.getAllDesignations();
  }

  getAllDesignations() {
    this.http.get(Constants.API_URL + 'designation/get').subscribe(data => {

        this.designation = <Designation>data;

      },
      error => {
        console.log(error);
      }
    );
  }
  checkId() {
    //
    if (Object.keys(this.designationForm).length === 0) {
      return true;
    }
    return false;
    // else {return true;}
  }

  editDsig(desig) {
    this.designationForm = desig;


  }

  reset() {
    this.designationForm = {} as Designation;
  }

  onSubmit() {

    const token = this.token.get();
    this.http.post(Constants.API_URL + 'designationinfo/post' + '?token=' + token, this.designationForm).subscribe(data => {

        this.getAllDesignations();

        this.designationForm = {} as Designation;
        $.alert({
          title: 'Success!',
          type: 'Green',
          content: data['message'],
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
