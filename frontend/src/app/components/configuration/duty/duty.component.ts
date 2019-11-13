import {Component, OnInit, AfterViewInit, Renderer, OnDestroy, ViewChild} from '@angular/core';
import {Constants} from '../../../constants';
import {HttpClient} from '@angular/common/http';
import {TokenService} from '../../../services/token.service';
import {Subject} from 'rxjs';
import {ActivatedRoute, Router} from '@angular/router';
import {DataTableDirective} from 'angular-datatables';
import {NgxSpinnerService} from 'ngx-spinner';
declare var $: any;

@Component({
  selector: 'app-duty',
  templateUrl: './duty.component.html',
  styleUrls: ['./duty.component.css']
})
export class DutyComponent implements OnInit {
  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;
  employee: any;
  dtOptions: DataTables.Settings = {};
  dtTrigger: Subject<any> = new Subject();
  dtInstance: DataTables.Api;

  // tslint:disable-next-line:max-line-length
  constructor(private renderer: Renderer, public http: HttpClient, private token: TokenService , public route: ActivatedRoute, private router: Router
    , private spinner: NgxSpinnerService) { }

  ngOnInit() {
    this.getData();
  }

  getData() {
    const token = this.token.get();
    this.dtOptions = {
      ajax: {
        url: Constants.API_URL + 'employee/get' + '?token=' + token,
        type: 'POST',
        data: function (d: any) {


        },
      },
      columns: [

        { data: 'firstName' , name: 'employeeinfo.firstName'},
        { data: 'attDeviceUserId' , name: 'attemployeemap.attDeviceUserId' },

        {
          'data': function (data: any, type: any, full: any) {
            // tslint:disable-next-line:max-line-length
            return ' <button class="btn btn-info" data-empUser-id="' + data.attDeviceUserId + '"> Calculate</button>&nbsp;&nbsp;<button class="btn btn-info" data-user-id="' + data.attDeviceUserId + '"> Download</button>';
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

  // tslint:disable-next-line:use-life-cycle-interface
  ngAfterViewInit(): void {
    this.dtTrigger.next();

    this.renderer.listenGlobal('document', 'click', (event) => {

      if (event.target.hasAttribute('data-empUser-id')) {

        const id = event.target.getAttribute('data-empUser-id');
        this.calculate(id);
      }
      if (event.target.hasAttribute('data-user-id')) {

        const id = event.target.getAttribute('data-user-id');
        this.download(id);
      }




    });


  }
  // tslint:disable-next-line:use-life-cycle-interface
  ngOnDestroy(): void {
    // Do not forget to unsubscribe the event
    this.dtTrigger.unsubscribe();
  }
  rerender() {
    this.dtElement.dtInstance.then((dtInstance: DataTables.Api) => {

      dtInstance.destroy();

      this.dtTrigger.next();
    });
  }
  calculate(id) {

    if (!this.checkForm()) {
      return false;
    } else {

      const form = {
        userId: id,
        date: $('#date').val(),


      };

      const token = this.token.get();
     this.spinner.show();

      this.http.post(Constants.API_URL + 'duty/calculateDuty' + '?token=' + token, form).subscribe(data => {

      //  console.log(data);

          this.spinner.hide();

          this.ngOnInit();
          this.rerender();

          $.alert({
            title: 'Success!',
            type: 'Green',
            content: 'Duty added successfully',
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
          console.log(error);
        }
      );

    }




  }
  download(id) {

    if (!this.checkForm()) {
      return false;
    } else {

      const form = {
        userId: id,
        date: $('#date').val(),


      };

      const token = this.token.get();
      this.spinner.show();

      this.http.post(Constants.API_URL + 'duty/download' + '?token=' + token, form).subscribe(data => {

          this.spinner.hide();
       // console.log(data);

          const fileName = Constants.Image_URL + 'exportedExcel/' + data;

          const link = document.createElement('a');
          link.download = data + '.xls';
          const uri = fileName + '.xls';
          link.href = uri;
          document.body.appendChild(link);
          link.click();
          document.body.removeChild(link);
          $('#excelType').val('');


          /* delete the server file */

          const fileinfo = {
            'filePath': 'exportedExcel/',
            'fileName': data + '.xls',
          };

          this.http.post(Constants.API_URL + 'deleteFile' + '?token=' + token, fileinfo).subscribe(data => {

              //  console.log(data);


            },
            error => {
              console.log(error);
            }
          );
        },
        error => {
          console.log(error);
        }
      );

    }

  }
  checkForm() {

    let message = '';
    let condition = true;
    if ($('#date').val() == '') {
      condition = false;
      message = 'Please Select a date to calculate';
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
