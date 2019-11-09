import { Component, OnInit } from '@angular/core';
import {ActivatedRoute} from '@angular/router';
declare var $: any;

@Component({
  selector: 'app-add-employee',
  templateUrl: './add-employee.component.html',
  styleUrls: ['./add-employee.component.css']
})
export class AddEmployeeComponent implements OnInit {

  infoFlag = 1;
  id: any;
  empid: any;
  error = [];

  constructor(public route: ActivatedRoute) { }

  ngOnInit() {
    this.empid = this.route.snapshot.params.id;
  }

  onInfoClick(value) {
    if (value == 1) {
      this.infoFlag = value;
    } else if (this.empid) {
      this.infoFlag = value;
    } else {
      // alert('Please Create a user first');

      $.alert({
        title: 'Alert!',
        type: 'Red',
        content: 'Please Create a user first',
        buttons: {
          tryAgain: {
            text: 'Ok',
            btnClass: 'btn-red',
            action: function () {
            }
          }
        }
      });
    }



  }

}
