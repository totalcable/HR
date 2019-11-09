import { Injectable } from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {Constants} from '../../app/constants';
import {TokenService} from './token.service';
import {NgxPermissionsService} from 'ngx-permissions';
import {NgxSpinnerService} from 'ngx-spinner';
import { Observable, of } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class RoleManagementService {

  Role: string;

  constructor(private permissionsService: NgxPermissionsService, public http: HttpClient,
              private spinner: NgxSpinnerService, private token: TokenService) {




  }
  setRole() {

    const token = this.token.get();

    this.http.post(Constants.API_URL + 'me?token=' + token, null).subscribe(data1 => {



        if (data1['fkUserType'] == 'emp') {


          this.http.post(Constants.API_URL + 'getEmpDesignation?token=' + token, {'id': data1['id']}).subscribe(data => {


              if (data['designationTitle'] == Constants.manager) {

                this.permissionsService.removePermission('emp');
                // console.log(this.permissionsService.getPermissions());


                const perm = [];
                perm.push(data['designationTitle']);
                this.permissionsService.loadPermissions(perm);




              }if (data['designationTitle'] == Constants.HR) {

                const perm = [];
                perm.push(data['designationTitle']);
                this.permissionsService.loadPermissions(perm);




              } else {




              }

              localStorage.setItem('role', data['designationTitle']);

              this.Role = data['designationTitle'];



            },
            error => {
              console.log(error);


            }
          );

        } else if (data1['fkUserType'] == 'admin') {

          localStorage.setItem('role', 'admin');
          this.Role = 'admin';


        }




      },
      error => {
        console.log(error);
        // this.handleError(error);

      }
    );

    return this.Role;

  }
}

