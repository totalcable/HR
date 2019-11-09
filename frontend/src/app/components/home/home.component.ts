import { Component, OnInit } from '@angular/core';
import { CheckService } from '../../services/check.service';
import {TokenService} from '../../services/token.service';
import {HttpClient} from '@angular/common/http';
import {ActivatedRoute, Router} from '@angular/router';
import {Constants} from '../../constants';
import { RoleManagementService } from '../../services/role-management.service';
declare var $: any;

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css']
})
export class HomeComponent implements OnInit {

  showTotalDiv: boolean;
  showTotalDiv1: boolean;
  designation: any;
  activeEmp: any;

  InActiveEmp: any;

  Role: any;


  // tslint:disable-next-line:max-line-length
  constructor(private check: CheckService, public http: HttpClient, private token: TokenService , public route: ActivatedRoute,
              private router: Router, private roleService: RoleManagementService) {

  }


  ngOnInit() {



    if (localStorage.getItem('role') == 'admin') {

      this.showTotalDiv = true;
      this.showTotalDiv1 = true;
      this.designation = 'admin';

      this.getTotalActiveEmp();
      this.getTotalInActiveEmp();

    } else {

      this.showTotalDiv = false;
      this.designation = localStorage.getItem('role');
    }



  }



  getTotalActiveEmp() {
    const token = this.token.get();


    this.http.get(Constants.API_URL + 'employee/getTotalActiveEmp' + '?token=' + token).subscribe(data => {
    this.activeEmp = data;
      },
      error => {
        console.log(error);
      }
    );


  }
  getTotalInActiveEmp() {

    const token = this.token.get();
    this.http.get(Constants.API_URL + 'employee/getTotalInActiveEmp' + '?token=' + token).subscribe(data => {
        this.InActiveEmp = data;
      },
      error => {
        console.log(error);
      }
    );

  }




}
