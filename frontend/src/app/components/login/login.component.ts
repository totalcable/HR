import { Component, OnInit } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import {Router} from '@angular/router';
import  { Constants }  from '../../constants';
import {TokenService} from '../../services/token.service';
import {FormGroup, FormControl, Validators} from '@angular/forms';
import {NgxSpinnerService} from 'ngx-spinner';
import { NgxPermissionsService, NgxPermissionsConfigurationService } from 'ngx-permissions';
import {User} from '../../model/user.model';


@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit {
    form: any;
    error: null;
    submitted = false;
    userModel = {} as User;



  constructor(private http: HttpClient,
              private router: Router,
              private token: TokenService,
              private spinner: NgxSpinnerService,
              private permissionsService: NgxPermissionsService,
              private ngxPermissionsConfigurationService: NgxPermissionsConfigurationService,
  ) {

  }

  ngOnInit() {

      this.form = new FormGroup({
          email: new FormControl('', [
              Validators.required,
              Validators.email
          ]),
          password: new FormControl('', [
              Validators.required
          ])
      });

  }

  onSubmit(value) {
      this.submitted = true;
      if (!this.form.valid) {
          return;
      }
      this.spinner.show();
    this.http.post(Constants.API_URL + 'login', value).subscribe(data => {
        this.spinner.hide();

          this.error = null;
          this.handleResponse(data);

        },
        error => {
            this.spinner.hide();

          console.log(error.error['error']);
          if (error.statusText == 'Unauthorized') {
              this.error = error.error['error'];
          }


        }
    );
  }

  get f() { return this.form.controls; }

  handleResponse(data) {

    this.token.handle(data.access_token);
    this.token.getUser().subscribe(data => {
              this.userModel = data as User;
              const perm = [];
              perm.push(this.userModel.fkUserType);
              console.log(perm);
              this.permissionsService.loadPermissions(perm);

          },
          error => {
              console.log(error);



    });



    this.router.navigateByUrl('home');
  }

}
