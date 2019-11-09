import {Component, OnInit, AfterViewInit, Renderer, OnDestroy, ViewChild} from '@angular/core';
import {Constants} from '../../../constants';
import {HttpClient} from '@angular/common/http';
import {TokenService} from '../../../services/token.service';
import {Subject} from 'rxjs';
import {ActivatedRoute, Router} from '@angular/router';
import {DataTableDirective} from 'angular-datatables';
import {NgbModal} from '@ng-bootstrap/ng-bootstrap';


declare var $: any;

@Component({
  selector: 'app-punch-time-edit',
  templateUrl: './punch-time-edit.component.html',
  styleUrls: ['./punch-time-edit.component.css']
})
export class PunchTimeEditComponent implements OnInit {

  employee: any;
  dropdownSettings = {};
  selectedItems = [];
  date: any;
  empRoster: any;

  constructor(private modalService: NgbModal, private renderer: Renderer, public http: HttpClient,
              private token: TokenService , public route: ActivatedRoute, private router: Router) { }

  ngOnInit() {
    this.getAllEployee();

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

  }

  getAllEployee() {

    const token = this.token.get();


    this.http.get(Constants.API_URL + 'employee/getAll' + '?token=' + token).subscribe(data => {
        this.employee = data;

      },
      error => {
        console.log(error);
      }
    );

  }

  findRosterAndPunch() {

    const token = this.token.get();

    const form = {
      'empId': this.selectedItems[0]['empid'],
      'date': $('#date').val()
    };


    this.http.post(Constants.API_URL + 'punch/getEmpRosterAndPunches' + '?token=' + token, form).subscribe(data => {

      // this.empRoster = data;
       console.log(data);

      },
      error => {
        console.log(error);
      }
    );

  }
  // empPunches(shiftLogId) {
  //
  //   const token = this.token.get();
  //
  //   const form = {
  //     'empId': this.selectedItems[0]['empid'],
  //     'date': $('#date').val(),
  //     'shiftLog': shiftLogId
  //   };
  //
  //
  //   this.http.post(Constants.API_URL + 'punch/getEmpPunches' + '?token=' + token, form).subscribe(data => {
  //
  //       this.empRoster = data;
  //
  //     },
  //     error => {
  //       console.log(error);
  //     }
  //   );
  //
  // }



}
