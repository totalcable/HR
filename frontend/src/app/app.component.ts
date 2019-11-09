import { Component , OnInit, Renderer2 } from '@angular/core';
import {TokenService} from './services/token.service';
import {Constants} from './constants';
import {HttpClient} from '@angular/common/http';
import { NgxPermissionsService, NgxPermissionsConfigurationService } from 'ngx-permissions';
import {User} from './model/user.model';
import {HashLocationStrategy, Location, LocationStrategy} from '@angular/common';
import {Router} from '@angular/router';
import { NavbarService } from '../app/services/navbar.service';


@Component({
    selector: 'app-root',
    templateUrl: './app.component.html',
    styleUrls: ['./app.component.css']
})
export class AppComponent {
    title = 'app';
    master = 'Master';
    userModel = {} as User;
    permission: string[] = ['guest'];
    loadPage = false;




    constructor(private permissionsService: NgxPermissionsService,
                private ngxPermissionsConfigurationService: NgxPermissionsConfigurationService,
                private token: TokenService, public http: HttpClient, private renderer2: Renderer2,
                private router: Router,
                public nav: NavbarService
    ) {



        this.nav.show();




        this.token.isValid();
        setTimeout(() => {
           // console.log("setted");
            this.loadPage = true;


        }, 2000);

        // const token=this.token.get();
        this.http.post(Constants.API_URL + 'me?token=' + token.get(), null).subscribe(data => {
                // console.log(data);

                localStorage.setItem('user', JSON.stringify(data));

                const perm = [];
                perm.push(token.getUserLocal().fkUserType);
                permissionsService.loadPermissions(perm);


            },
            error => {
                console.log(error);
                // this.handleError(error);

            }
        );

    }

    ngOnInit(): void {



    }

    isLogIn() {


        return this.token.isValid();
    }



    public unAuthorized() {
        console.log('unAuthorized');
    }

    public authorized() {
        console.log('authorizes');
    }

    public addPermission() {
        this.permissionsService.addPermission('CHECK_LOAD');
    }

    changeToAdmin() {
        this.permission = ['ADMIN'];
        console.log(this.permission);
    }

    changeToAnotherPermission() {
        this.permission = ['AWESOME'];
    }

    changeToGuest() {
        this.permission = ['GUEST'];
        console.log(this.permission);
    }


}
