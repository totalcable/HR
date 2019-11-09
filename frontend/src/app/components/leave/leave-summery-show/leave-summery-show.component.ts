import {Component, Input, OnInit} from '@angular/core';
import {Constants} from '../../../constants';
import {HttpClient} from '@angular/common/http';
import {TokenService} from '../../../services/token.service';
import {ActivatedRoute, Router} from '@angular/router';
declare var $: any;

@Component({
  selector: 'app-leave-summery-show',
  templateUrl: './leave-summery-show.component.html',
  styleUrls: ['./leave-summery-show.component.css']
})
export class LeaveSummeryShowComponent implements OnInit {

  empid: any;
  myLeaves: any;
  details: any = {};

  constructor(public http: HttpClient, private token: TokenService , public route: ActivatedRoute, private router: Router) { }

  ngOnInit() {

    this.empid = this.route.snapshot.params.id;
    this.getMyLeaves();

  }

  getMyLeaves() {
    //For name
    const token = this.token.get();
    this.http.post(Constants.API_URL + 'employee/basicinfo' + '?token=' + token, { empid: this.empid}).subscribe(data => {

        console.log(data);
        this.details = data;
      },
      error => {
        console.log(error);
      }
    );


    //For Leave List
    this.http.post(Constants.API_URL + 'leave/summery/details' + '?token=' + token, {id: this.empid}).subscribe(data => {
        this.myLeaves = data;
      },
      error => {
        console.log(error);
      }
    );
  }

}
