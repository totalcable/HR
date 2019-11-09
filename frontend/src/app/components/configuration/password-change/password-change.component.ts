import {Component, OnInit, AfterViewInit, Renderer, OnDestroy, ViewChild} from '@angular/core';
import {Constants} from '../../../constants';
import {HttpClient} from '@angular/common/http';
import {TokenService} from '../../../services/token.service';
import {Subject} from 'rxjs';
import {ActivatedRoute, Router} from '@angular/router';
import {DataTableDirective} from 'angular-datatables';
import {NgbModal} from '@ng-bootstrap/ng-bootstrap';
import {NgxSpinnerService} from 'ngx-spinner';

declare var $: any;

@Component({
  selector: 'app-password-change',
  templateUrl: './password-change.component.html',
  styleUrls: ['./password-change.component.css']
})
export class PasswordChangeComponent implements OnInit {

  dropdownSettings = {};
  selectedItems = [];
  employee: any;

  constructor(private modalService: NgbModal, private renderer: Renderer, public http: HttpClient, private token: TokenService ,
              public route: ActivatedRoute, private router: Router, private spinner: NgxSpinnerService) { }

  ngOnInit() {

    this.dropdownSettings = {
      singleSelection: true,
      idField: 'empid',
      textField: 'attDeviceUserId',
      // selectAllText: 'Select All',
      // unSelectAllText: 'UnSelect All',
      // itemsShowLimit: 3,
      allowSearchFilter: true,
      closeDropDownOnSelection: true,
    };
    this.getAllEployee();

  }

  getAllEployee() {

    const token = this.token.get();


    this.http.get(Constants.API_URL + 'employee/getAll' + '?token=' + token).subscribe(data => {
        this.employee = data;
        // console.log(data);
      },
      error => {
        console.log(error);
      }
    );

  }

  submitPasswordChange() {

    if (!this.checkPasswordChangeForm()) {
      return false;
    } else {



      const form = {
        'empId': this.selectedItems[0]['empid'],

        'new_password': $('#new_passwordEmp').val(),

      };

      const token = this.token.get();
      this.spinner.show();
      this.http.post(Constants.API_URL + 'password/changePasswordFromAdmin?token=' + token, form).subscribe(data => {

          this.spinner.hide();



          $.alert({
            title: 'Alert!',
            type: 'Green',
            content: 'Password Change Successfully',
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



        }
      );


    }


  }

  checkPasswordChangeForm() {

    let message = '';
    let condition = true;


    if (this.selectedItems.length == 0) {

      condition = false;
      message = 'Please Select an Employee';

    }

    if ($('#new_passwordEmp').val() == '') {

      condition = false;
      message = 'Please insert a new Password';

    }
    if ($('#new_passwordEmp').val().length < 6 ) {

      condition = false;
      message = 'New Password should be atleast 6 charecter';

    }
    if ($('#confirm_new_passwordEmp').val() == '') {

      condition = false;
      message = 'Please insert a Confirm new Password';

    }
    if ($('#new_passwordEmp').val() != $('#confirm_new_passwordEmp').val()) {

      condition = false;
      message = 'new password and Confirm new password must be Same';

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

}
