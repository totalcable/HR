import { Component, OnInit } from '@angular/core';
import {Constants} from '../../constants';
import {HttpClient} from '@angular/common/http';
import {TokenService} from '../../services/token.service';
import {User} from '../../model/user.model';
import {NgxPermissionsService} from 'ngx-permissions';
import { NavbarService } from '../../services/navbar.service';
import {NgbModal} from '@ng-bootstrap/ng-bootstrap';
import {NgxSpinnerService} from 'ngx-spinner';

declare var $: any;

@Component({
  selector: 'app-navbar',
  templateUrl: './navbar.component.html',
  styleUrls: ['./navbar.component.css']
})
export class NavbarComponent implements OnInit {

  data: any;

  userModel = {} as User;
    user: any = {
        contactNo: '',
        email: '',
        fkActivationStatus: '',
        fkCompany: '',
        fkUserType: '',
        id:  '',
        picture: '',
        registrationdate:  '',
        rememberToken:  '',
        userName:  ''

    };
    tokenUser: any = {};
  permission: string;
  modalRef: any;

  constructor(private permissionsService: NgxPermissionsService, public http: HttpClient, private token: TokenService,
              public nav: NavbarService, private modalService: NgbModal, private spinner: NgxSpinnerService) {

  }

  ngOnInit() {

    const token = this.token.get();

          this.http.post(Constants.API_URL + 'me?token=' + token, null).subscribe(data1 => {



              if (data1['fkUserType'] == 'emp') {

                const token = this.token.get();
                this.http.post(Constants.API_URL + 'getEmpDesignation?token=' + token, {'id': data1['id']}).subscribe(data => {


                    if (data['designationTitle'] == Constants.manager) {

                      this.permissionsService.removePermission('emp');
                     // console.log(this.permissionsService.getPermissions());


                      const perm = [];
                      perm.push(data['designationTitle']);
                      this.permissionsService.loadPermissions(perm);

                    //  localStorage.setItem('role', data['designationTitle']);

                      // console.log(this.permissionsService.getPermissions());


                    }if (data['designationTitle'] == Constants.HR) {

                      const perm = [];
                      perm.push(data['designationTitle']);
                      this.permissionsService.loadPermissions(perm);

                   //   localStorage.setItem('role', data['designationTitle']);


                    } else {

                   //   localStorage.setItem('role', data['fkUserType']);


                    }

                    localStorage.setItem('role', data['designationTitle']);



                  },
                  error => {
                    console.log(error);


                  }
                );

              } else if (data1['fkUserType'] == 'admin') {

                localStorage.setItem('role', 'admin');


              }


            },
            error => {
              console.log(error);
              // this.handleError(error);

            }
          );

  }


    isAdmin() {
      if (this.user.fkUserType == 'admin') {
          return true;
      }
      //   console.log(this.user.fkUserType);
      return false;
    }

  whoAmI(e: MouseEvent) {
      e.preventDefault();


      const token = this.token.get();
      this.http.post(Constants.API_URL + 'me?token=' + token, null).subscribe(data => {
              console.log(data);

          },
          error => {
              console.log(error);
              this.handleError(error);

          }
      );
  }

  logout(e: MouseEvent) {
    e.preventDefault();

    const token = this.token.get();
    // console.log(token);
    //
    this.http.post(Constants.API_URL + 'logout?token=' + token, null).subscribe(data => {
          // console.log(data);
          this.data = data;
          if (this.data.flag === 'true') {
            this.token.remove();
          }

        },
        error => {

          if (error.status == 401 && error.error.message === 'Unauthenticated.') {
            this.token.remove();
          }

        }
    );


  }
  ChangePass(passwordChange) {

    this.modalRef = this.modalService.open(passwordChange, {size: 'lg', backdrop: 'static'});

  }
  submitPasswordChange() {

    if (!this.checkPasswordChangeForm()) {
      return false;
    } else {

      const user = JSON.parse(localStorage.getItem('user'));


      const form = {
        'userId': user.id,

        'new_password': $('#new_password').val(),

      };

      const token = this.token.get();
      this.spinner.show();
      this.http.post(Constants.API_URL + 'password/changePasswordFromUser?token=' + token, form).subscribe(data => {

        // console.log(data);
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


    // if ($('#old_password').val() == '') {
    //
    //   condition = false;
    //   message = 'Please insert old Password';
    //
    // }

    if ($('#new_password').val() == '') {

      condition = false;
      message = 'Please insert a new Password';

    }
    if ($('#new_password').val().length < 6 ) {

      condition = false;
      message = 'New Password should be atleast 6 charecter';

    }
    if ($('#confirm_new_password').val() == '') {

      condition = false;
      message = 'Please insert a Confirm new Password';

    }
    if ($('#new_password').val() != $('#confirm_new_password').val()) {

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

  handleError(error) {
      if (error.status == 401 && error.error.message === 'Unauthenticated.') {
          this.token.remove();
      }

  }

  databaseDownload() {

    const token = this.token.get();

    this.spinner.show();
    this.http.get(Constants.API_URL + 'database/backup' + '?token=' + token).subscribe(data => {

        this.spinner.hide();
         console.log(data);
        if (data['flag'] == '1') {

          $.alert({
            title: 'Success!',
            type: 'Green',
            content: data['msg'],
            buttons: {
              tryAgain: {
                text: 'Ok',
                btnClass: 'btn-green',
                action: function () {
                }
              }
            }
          });

          const fileName = Constants.Image_URL + 'DBbackup/' + data['fileName'];

          const link = document.createElement('a');

          link.download = data['fileName'] + '.sql';
          const uri = fileName + '.sql';
          link.href = uri;
          document.body.appendChild(link);
          link.click();
          document.body.removeChild(link);

        } else if (data['flag'] == '0') {

          $.alert({
            title: 'Alert!',
            type: 'Red',
            content: data['msg'],
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


      },
      error => {
        console.log(error);
      }
    );

  }

}
