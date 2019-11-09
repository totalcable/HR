import {Component, OnInit, AfterViewInit, Renderer} from '@angular/core';
import {Constants} from '../../../constants';
import {HttpClient} from '@angular/common/http';
import {TokenService} from '../../../services/token.service';
import {Subject} from 'rxjs';
import {ActivatedRoute, Router} from '@angular/router';

@Component({
  selector: 'app-employee',
  templateUrl: './employee.component.html',
  styleUrls: ['./employee.component.css']
})
export class EmployeeComponent implements AfterViewInit, OnInit {

  employee: any;
  dtOptions: DataTables.Settings = {};
  dtTrigger: Subject<any> = new Subject();
  id: any;

  constructor(private renderer: Renderer, public http: HttpClient, private token: TokenService , public route: ActivatedRoute, private router: Router) { }

  ngOnInit() {

    const token = this.token.get();
    this.dtOptions = {
      ajax: {
        url: Constants.API_URL + 'employee/get' + '?token=' + token,
        type: 'POST'
      },
      columns: [
        { data: 'attDeviceUserId' , name: 'attemployeemap.attDeviceUserId'},
        { data: 'firstName' , name: 'employeeinfo.firstName'},
        { data: 'middleName' , name: 'employeeinfo.middleName'},
        { data: 'lastName' , name: 'employeeinfo.lastName'},
        { data: 'EmployeeId' , name: 'employeeinfo.EmployeeId' },
        { data: 'title', name: 'designations.title'},
        { data: 'departmentName', name: 'departments.departmentName'},
        {

          'data': function (data: any, type: any, full: any) {
            return ' <button class="btn btn-info" data-emp-id="' + data.empid + '"> Edit</button>&nbsp;&nbsp;' +
              '<button class="btn btn-info" data-emp-info="' + data.empid + '"> view Info</button>';
          },
          'orderable': false, 'searchable': false, 'name': 'selected_rows'
        }

      ],


      processing: true,
      serverSide: true,
      pagingType: 'full_numbers',
      pageLength: 10
    };
  }
  //
  ngAfterViewInit(): void {

    this.dtTrigger.next();
    this.renderer.listenGlobal('document', 'click', (event) => {

      if (event.target.hasAttribute('data-emp-id')) {
        this.router.navigate(['employee/edit/' + event.target.getAttribute('data-emp-id')]);
      }
      if (event.target.hasAttribute('data-emp-info')) {

        this.viewEmpInfoPdf(event.target.getAttribute('data-emp-info'));

      //  this.router.navigate(['employee/view-emp-info-pdf/' + event.target.getAttribute('data-emp-info')]);

      }


    });

  }
  viewEmpInfoPdf(empId) {

    const token = this.token.get();

    this.http.post(Constants.API_URL + 'employee/viewEmpInfoPdf' + '?token=' + token, {id: empId}).subscribe(data => {


        const fileName = Constants.Image_URL + 'employeeInfoPDF/' + data;

        const uri = fileName + '.pdf';

        window.open(uri, '_blank');


      },
      error => {
        console.log(error);
      }
    );



  }


  // getAllemployee(){
  //   const token=this.token.get();
  //
  //   this.http.get(Constants.API_URL+'employee/get'+'?token='+token).subscribe(data => {
  //       // console.log(data);
  //       this.employee=data;
  //       this.dtTrigger.next();
  //       // console.log(data);
  //     },
  //     error => {
  //       console.log(error);
  //     }
  //   );
  //
  // }

}
