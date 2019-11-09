import {Component, OnInit, AfterViewInit, Renderer, OnDestroy, ViewChild} from '@angular/core';
import {Constants} from '../../../constants';
import {HttpClient} from '@angular/common/http';
import {TokenService} from '../../../services/token.service';
import {Subject} from 'rxjs';
import {ActivatedRoute, Router} from '@angular/router';
import {DataTableDirective} from 'angular-datatables';
import {NgxSpinnerService} from 'ngx-spinner';
import {st} from '@angular/core/src/render3';
import {log} from 'util';
import {isEmpty} from 'rxjs/operators';


declare var $: any;

@Component({
  selector: 'app-attendance',
  templateUrl: './attendance.component.html',
  styleUrls: ['./attendance.component.css']
})
export class AttendanceComponent implements OnInit {
  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;
  employee: any;
  dtOptions: DataTables.Settings = {};
  dtTrigger: Subject<any> = new Subject();
  id: any;
  allEmp = [];
  shiftId: number;
  shift: any;
  dtInstance: DataTables.Api;
  startDate: string;
  endDate: string;
  noOfDays: string;
  remark: string;
  fkLeaveCategory: string;
  leaveCategories: any;
  dropdownSettings = {};
  dropdownSettings2 = {};
  selectedItems = [];
  selectedDropDown = [];
  attendanceData: any;
  attendanceDate: any;
  test: any;
  search: boolean;
  departments: any;
  emp: any;
  attendenceDataDates: any;
  attendenceDataResults: any;
  attendenceDataAllEmp: any;
  RosterInfo: any;
  empDiv: boolean;
  rosterDiv: boolean;


  constructor(private renderer: Renderer, public http: HttpClient, private token: TokenService ,
              public route: ActivatedRoute, private router: Router, private spinner: NgxSpinnerService) { }


  ngOnInit() {
    this.RosterInfo = '';
    this.search = false;
    this.empDiv = true;
    this.rosterDiv = false;

    this.getAllEployee();
    this.getAllDepartment();

    this.attendenceDataDates = '';
    this.attendenceDataResults = '';
    this.attendenceDataAllEmp = '';

   // this.getData();
    // console.log(new Date.today().clearTime().moveToFirstDayOfMonth());
    const nowdate = new Date();
    const monthStartDay = this.dateToYMD(new Date(nowdate.getFullYear(), nowdate.getMonth(), 1));
    const monthEndDay = this.dateToYMD(new Date(nowdate.getFullYear(), nowdate.getMonth() + 1, 0));
    $('#startDate').val(monthStartDay);
    $('#endDate').val(monthEndDay);
    // console.log(monthEndDay);
    // console.log(monthStartDay);

    this.dropdownSettings = {
      singleSelection: false,
      idField: 'empid',
      textField: 'attDeviceUserId',
      // selectAllText: 'Select All',
      // unSelectAllText: 'UnSelect All',
      // itemsShowLimit: 3,
      allowSearchFilter: true,
      closeDropDownOnSelection: true,
    };
    this.dropdownSettings2 = {
      singleSelection: false,
      idField: 'id',
      textField: 'departmentName',
      selectAllText: 'Select All',
      unSelectAllText: 'UnSelect All',
      // itemsShowLimit: 3,
      allowSearchFilter: true,
      closeDropDownOnSelection: true,
    };




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

  onItemSelect(value) {



  }
  onItemDeSelect(value) {



  }
  onItemSelectDepartment(value) {

    this.selectedItems = [];
    this.empDiv = false;




    if (this.selectedDropDown.length > 0) {

      this.rosterDiv = true;

      const deptId = [];

      for (let i = 0; i < this.selectedDropDown.length; i++) {

        deptId.push(this.selectedDropDown[i]['id']);
      }

      const form = {
        departments: deptId,

      };

      const token = this.token.get();


      this.http.post(Constants.API_URL + 'employee/getAllEmpForDepartment' + '?token=' + token, form).subscribe(data => {

        this.emp = data;
        if (this.emp.length > 0) {

          for (let i = 0; i < this.emp.length; i++) {

            const r = {
              empid: this.emp[i]['empid'],
              attDeviceUserId: this.emp[i]['attDeviceUserId']

            };


            this.selectedItems.push(r);

          }



        }

         // console.log(this.selectedItems);

        },
        error => {
          console.log(error);
        }
      );

      this.http.post(Constants.API_URL + 'department/getRosterInfo' + '?token=' + token, form).subscribe(data => {

        this.RosterInfo = data;



        },
        error => {
          console.log(error);
        }
      );


    }



  }
  onItemDeSelectDepartment(value) {

    if (this.selectedDropDown.length > 0) {
      // this.selectedItems=[];

      const deptId = [];

      for (let i = 0; i < this.selectedDropDown.length; i++) {

        deptId.push(this.selectedDropDown[i]['id']);
      }

      const form = {
        departments: deptId,

      };

      const token = this.token.get();


      this.http.post(Constants.API_URL + 'employee/getAllEmpForDepartment' + '?token=' + token, form).subscribe(data => {

          this.emp = data;
          if (this.emp.length > 0) {

            for (let i = 0; i < this.emp.length; i++) {

              const r = {

                empid: this.emp[i]['empid'],
                attDeviceUserId: this.emp[i]['attDeviceUserId']

              };


              this.selectedItems.push(r);



            }

          }





        },
        error => {
          console.log(error);
        }
      );


    } else {
      this.selectedItems = [];

      this.empDiv = true;
      this.rosterDiv = false;


    }

  }
  getAllDepartment() {

    const token = this.token.get();


    this.http.get(Constants.API_URL + 'department/get' + '?token=' + token).subscribe(data => {

        this.departments = data;

      },
      error => {
        console.log(error);
      }
    );

  }



  dateToYMD(date) {
    const strArray = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
    const d = date.getDate();
    const m = strArray[date.getMonth()];
    const y = date.getFullYear();
    // return '' + (d <= 9 ? '0' + d : d) + '-' + m + '-' + y;
    return '' + y + '-' + m + '-' + (d <= 9 ? '0' + d : d);
  }

  dateToYMD2(date) {
    const strArray = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
    const d = date.getDate();
    const m = strArray[date.getMonth()];
    const y = date.getFullYear();
    return '' + (d <= 9 ? '0' + d : d) + '-' + m + '-' + y;
  }





  generateDetailsExcel() {
    this.spinner.show();
    const token = this.token.get();

    this.http.post(Constants.API_URL + 'report/attendanceHR' + '?token=' + token, {startDate: $('#startDate').val(), endDate: $('#endDate').val()}).subscribe(data => {

        this.spinner.hide();
        console.log(data);


        const fileName = Constants.Image_URL + 'exportedExcel/' + data;

        const link = document.createElement('a');
        link.download = data + '.xls';
        const uri = fileName + '.xls';
        link.href = uri;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);


      },
      error => {
        console.log(error);
        this.spinner.hide();
      }
    );

  }
  generateMonthlyINOUTExcel() {

    this.spinner.show();
    const token = this.token.get();

    this.http.post(Constants.API_URL + 'report/attendanceHRINOUTmonthly' + '?token=' + token, {report: 'monthly', startDate: $('#startDate').val(), endDate: $('#endDate').val()}).subscribe(data => {

        this.spinner.hide();
        console.log(data);


        const fileName = Constants.Image_URL + 'exportedExcel/' + data;

        const link = document.createElement('a');
        link.download = data + '.xls';
        const uri = fileName + '.xls';
        link.href = uri;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);


      },
      error => {
        console.log(error);
        this.spinner.hide();
      }
    );

  }
  generateINOUTExcel() {

    if ($('#notAssignedRoster').is(':checked')) {

      if ($('#excelType').val() == '') {

        $.alert({
          title: 'Alert',
          content: 'Please select Excel Type',
        });

      } else {

        // this.spinner.show();
        // const token = this.token.get();
        //
        // this.http.post(Constants.API_URL + 'report/attendanceHRINOUT' + '?token=' + token, {
        //   startDate: $('#startDate').val(),
        //   endDate: $('#endDate').val(),
        //   report: 'notAssignedRoster'
        // }).subscribe(data => {
        //
        //     this.spinner.hide();
        //     console.log(data);
        //
        //
        //     let fileName = Constants.Image_URL + 'exportedExcel/' + data;
        //
        //     let link = document.createElement("a");
        //     link.download = data + ".xls";
        //     let uri = fileName + ".xls";
        //     link.href = uri;
        //     document.body.appendChild(link);
        //     link.click();
        //     document.body.removeChild(link);
        //     $("#excelType").val("");
        //     this.selectedItems = [];
        //
        //
        //   },
        //   error => {
        //     console.log(error);
        //     this.spinner.hide();
        //   }
        // );

      }






    } else {


      if (this.selectedItems.length > 0) {

      //  console.log(this.selectedItems);


        if ($('#excelType').val() == '') {

          $.alert({
            title: 'Alert',
            content: 'Please select Excel Type',
          });

        } else {

          const empList = [];
          for (let $i = 0; $i < this.selectedItems.length; $i++) {
            empList.push(this.selectedItems[$i]['empid']);
          }
          // console.log(empList);

          this.spinner.show();
          const token = this.token.get();

          if ($('#excelType').val() == '1') {


            this.http.post(Constants.API_URL + 'report/attendanceHRINOUT' + '?token=' + token, {
              startDate: $('#startDate').val(),
              endDate: $('#endDate').val(),
              empId: empList,
              report: 'dailyINOUT'
            }).subscribe(data => {

                this.spinner.hide();
                console.log(data);


                const fileName = Constants.Image_URL + 'exportedExcel/' + data;

                const link = document.createElement('a');
                link.download = data + '.xls';
                const uri = fileName + '.xls';
                link.href = uri;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                $('#excelType').val('');
                this.selectedItems = [];

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
                this.spinner.hide();
              }
            );

          } else if ($('#excelType').val() == '2') {


            this.http.post(Constants.API_URL + 'report/attendanceHRINOUT' + '?token=' + token, {
              startDate: $('#startDate').val(),
              endDate: $('#endDate').val(),
              empId: empList
            }).subscribe(data => {

                this.spinner.hide();
                console.log(data);


                const fileName = Constants.Image_URL + 'exportedExcel/' + data;

                const link = document.createElement('a');
                link.download = data + '.xls';
                const uri = fileName + '.xls';
                link.href = uri;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                $('#excelType').val('');
                this.selectedItems = [];

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
                this.spinner.hide();
              }
            );


          } else if ($('#excelType').val() == '3') {

            this.http.post(Constants.API_URL + 'report/attendanceHRINOUT' + '?token=' + token, {
              startDate: $('#startDate').val(),
              endDate: $('#endDate').val(),
              empId: empList,
              report: 'monthlyINOUT'
            }).subscribe(data => {

                this.spinner.hide();
                console.log(data);


                const fileName = Constants.Image_URL + 'exportedExcel/' + data;

                const link = document.createElement('a');
                link.download = data + '.xls';
                const uri = fileName + '.xls';
                link.href = uri;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                $('#excelType').val('');
                this.selectedItems = [];

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
                this.spinner.hide();
              }
            );

          } else if ($('#excelType').val() == '4') {

            this.http.post(Constants.API_URL + 'report/finalReport-1' + '?token=' + token, {

              startDate: $('#startDate').val(),
              endDate: $('#endDate').val(),
              empId: empList,
              report: 'final_Report_1'
            }).subscribe(data => {

                this.spinner.hide();
                console.log(data);


                const fileName = Constants.Image_URL + 'exportedExcel/' + data;

                const link = document.createElement('a');
                link.download = data + '.xls';
                const uri = fileName + '.xls';
                link.href = uri;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                $('#excelType').val('');
                this.selectedItems = [];

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
                this.spinner.hide();
              }
            );

          } else if ($('#excelType').val() == '5') {

            this.http.post(Constants.API_URL + 'report/finalReport-2' + '?token=' + token, {

              startDate: $('#startDate').val(),
              endDate: $('#endDate').val(),
              empId: empList,
              report: 'final_Report_2'
            }).subscribe(data => {

                this.spinner.hide();
                console.log(data);

                const fileName = Constants.Image_URL + 'exportedExcel/' + data;

                const link = document.createElement('a');
                link.download = data + '.xls';
                const uri = fileName + '.xls';
                link.href = uri;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                $('#excelType').val('');
                this.selectedItems = [];

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
                this.spinner.hide();
              }
            );

          } else if ($('#excelType').val() == '6') {

            this.http.post(Constants.API_URL + 'report/finalReport-3' + '?token=' + token, {

              startDate: $('#startDate').val(),
              endDate: $('#endDate').val(),
              empId: empList,
              report: 'final_Report_3'
            }).subscribe(data => {

                this.spinner.hide();
                console.log(data);


                const fileName = Constants.Image_URL + 'exportedExcel/' + data;

                const link = document.createElement('a');
                link.download = data + '.xls';
                const uri = fileName + '.xls';
                link.href = uri;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                $('#excelType').val('');
                this.selectedItems = [];

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
                this.spinner.hide();
              }
            );

          } else if ($('#excelType').val() == '7') {




            if ($('#RosterInfo').find(':selected').val() == '' || $('#RosterInfo').find(':selected').val() == null) {

              $.alert({
                title: 'Alert',
                content: 'Please select a Roster',
              });
              this.spinner.hide();

            } else {

              this.http.post(Constants.API_URL + 'report/RoserWiseReport-1' + '?token=' + token, {

                startDate: $('#startDate').val(),
                endDate: $('#endDate').val(),
                empId: empList,
                rosterId: $('#RosterInfo').find(':selected').val(),
                report: 'Roster_wise_Report'

              }).subscribe(data => {

                  this.spinner.hide();
                  console.log(data);


                  const fileName = Constants.Image_URL + 'exportedExcel/' + data;

                  const link = document.createElement('a');
                  link.download = data + '.xls';
                  const uri = fileName + '.xls';
                  link.href = uri;
                  document.body.appendChild(link);
                  link.click();
                  document.body.removeChild(link);
                  $('#excelType').val('');
                  this.selectedItems = [];
                  this.selectedDropDown = [];
                  $('#RosterInfo').val('');
                  this.rosterDiv = false;
                  this.empDiv = true;

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
                  this.spinner.hide();
                }
              );


            }



          } else if ($('#excelType').val() == '9') {




            if ($('#RosterInfo').find(':selected').val() == '' || $('#RosterInfo').find(':selected').val() == null) {

              $.alert({
                title: 'Alert',
                content: 'Please select a Roster',
              });
              this.spinner.hide();

            } else {

              this.http.post(Constants.API_URL + 'report/RoserWiseReport-3' + '?token=' + token, {

                startDate: $('#startDate').val(),
                endDate: $('#endDate').val(),
                empId: empList,
                rosterId: $('#RosterInfo').find(':selected').val(),
                report: 'Roster_wise_Report-3'

              }).subscribe(data => {

                  this.spinner.hide();
                  console.log(data);


                  const fileName = Constants.Image_URL + 'exportedExcel/' + data;

                  const link = document.createElement('a');
                  link.download = data + '.xls';
                  const uri = fileName + '.xls';
                  link.href = uri;
                  document.body.appendChild(link);
                  link.click();
                  document.body.removeChild(link);
                  $('#excelType').val('');
                  this.selectedItems = [];
                  this.selectedDropDown = [];
                  $('#RosterInfo').val('');
                  this.rosterDiv = false;
                  this.empDiv = true;

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
                  this.spinner.hide();
                }
              );


            }



          } else if ($('#excelType').val() == '8') {




            if ($('#RosterInfo').find(':selected').val() == '' || $('#RosterInfo').find(':selected').val() == null) {

              $.alert({
                title: 'Alert',
                content: 'Please select a Roster',
              });
              this.spinner.hide();

            } else {

              this.http.post(Constants.API_URL + 'report/RoserWiseReport-2' + '?token=' + token, {

                startDate: $('#startDate').val(),
                endDate: $('#endDate').val(),
                empId: empList,
                rosterId: $('#RosterInfo').find(':selected').val(),
                report: 'Roster_wise_Report-2'

              }).subscribe(data => {

                  this.spinner.hide();
                  console.log(data);


                  const fileName = Constants.Image_URL + 'exportedExcel/' + data;

                  const link = document.createElement('a');
                  link.download = data + '.xls';
                  const uri = fileName + '.xls';
                  link.href = uri;
                  document.body.appendChild(link);
                  link.click();
                  document.body.removeChild(link);
                  $('#excelType').val('');
                  this.selectedItems = [];
                  this.selectedDropDown = [];
                  $('#RosterInfo').val('');
                  this.rosterDiv = false;
                  this.empDiv = true;

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
                  this.spinner.hide();
                }
              );


            }



          }
          // else if ($('#excelType').val() == "10") {
          //
          //
          //     this.http.post(Constants.API_URL + 'report/MultipleRoserWiseReport-1' + '?token=' + token, {
          //
          //       startDate: $('#startDate').val(),
          //       endDate: $('#endDate').val(),
          //       empId: empList,
          //       report: 'Multiple_Roster_wise_Report-1'
          //
          //     }).subscribe(data => {
          //
          //         this.spinner.hide();
          //         console.log(data);
          //
          //
          //         let fileName = Constants.Image_URL + 'exportedExcel/' + data;
          //
          //         let link = document.createElement("a");
          //         link.download = data + ".xls";
          //         let uri = fileName + ".xls";
          //         link.href = uri;
          //         document.body.appendChild(link);
          //         link.click();
          //         document.body.removeChild(link);
          //         $("#excelType").val("");
          //         this.selectedItems = [];
          //         this.selectedDropDown = [];
          //         $("#RosterInfo").val("");
          //         this.rosterDiv=false;
          //         this.empDiv=true;
          //
          //         /* delete the server file */
          //
          //       let fileinfo={
          //         'filePath':'exportedExcel/',
          //         'fileName':data + ".xls",
          //       }
          //
          //         this.http.post(Constants.API_URL+'deleteFile'+'?token='+token,fileinfo).subscribe(data => {
          //
          //         //  console.log(data);
          //
          //
          //           },
          //           error => {
          //             console.log(error);
          //           }
          //         );
          //
          //
          //       },
          //       error => {
          //         console.log(error);
          //         this.spinner.hide();
          //       }
          //     );
          //
          //
          //
          //
          // }


        }

      } else {

        if ($('#excelType').val() == '') {

          $.alert({
            title: 'Alert',
            content: 'Please select Excel Type',
          });

        } else if ($('#excelType').val() == '1') {

          this.spinner.show();
          const token = this.token.get();

          this.http.post(Constants.API_URL + 'report/attendanceHRINOUT' + '?token=' + token, {
            report: 'dailyINOUT',
            startDate: $('#startDate').val(),
            endDate: $('#endDate').val()
          }).subscribe(data => {

              this.spinner.hide();
              console.log(data);


              const fileName = Constants.Image_URL + 'exportedExcel/' + data;

              const link = document.createElement('a');
              link.download = data + '.xls';
              const uri = fileName + '.xls';
              link.href = uri;
              document.body.appendChild(link);
              link.click();
              document.body.removeChild(link);
              $('#excelType').val('');
              this.selectedItems = [];

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
              this.spinner.hide();
            }
          );

        } else if ($('#excelType').val() == '2') {

          this.spinner.show();
          const token = this.token.get();

          this.http.post(Constants.API_URL + 'report/attendanceHRINOUT' + '?token=' + token, {
            report: 'dailyINOUT',
            startDate: $('#startDate').val(),
            endDate: $('#endDate').val()
          }).subscribe(data => {

              this.spinner.hide();
              console.log(data);


              const fileName = Constants.Image_URL + 'exportedExcel/' + data;

              const link = document.createElement('a');
              link.download = data + '.xls';
              const uri = fileName + '.xls';
              link.href = uri;
              document.body.appendChild(link);
              link.click();
              document.body.removeChild(link);
              $('#excelType').val('');
              this.selectedItems = [];

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
              this.spinner.hide();
            }
          );


        } else if ($('#excelType').val() == '3') {

          this.spinner.show();
          const token = this.token.get();

          this.http.post(Constants.API_URL + 'report/attendanceHRINOUT' + '?token=' + token, {
            report: 'monthlyINOUT',
            startDate: $('#startDate').val(),
            endDate: $('#endDate').val()
          }).subscribe(data => {

              this.spinner.hide();
              console.log(data);


              const fileName = Constants.Image_URL + 'exportedExcel/' + data;

              const link = document.createElement('a');
              link.download = data + '.xls';
              const uri = fileName + '.xls';
              link.href = uri;
              document.body.appendChild(link);
              link.click();
              document.body.removeChild(link);
              $('#excelType').val('');
              this.selectedItems = [];

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
              this.spinner.hide();
            }
          );


        } else if ($('#excelType').val() == '4') {

          this.spinner.show();
          const token = this.token.get();

          this.http.post(Constants.API_URL + 'report/finalReport-1' + '?token=' + token, {

            startDate: $('#startDate').val(),
            endDate: $('#endDate').val(),

            report: 'final_Report_1'
          }).subscribe(data => {

              this.spinner.hide();
              console.log(data);


              const fileName = Constants.Image_URL + 'exportedExcel/' + data;

              const link = document.createElement('a');
              link.download = data + '.xls';
              const uri = fileName + '.xls';
              link.href = uri;
              document.body.appendChild(link);
              link.click();
              document.body.removeChild(link);
              $('#excelType').val('');
              this.selectedItems = [];

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
              this.spinner.hide();
            }
          );

        } else if ($('#excelType').val() == '6') {

          this.spinner.show();
          const token = this.token.get();

          this.http.post(Constants.API_URL + 'report/finalReport-3' + '?token=' + token, {

            startDate: $('#startDate').val(),
            endDate: $('#endDate').val(),

            report: 'final_Report_3'
          }).subscribe(data => {

              this.spinner.hide();
              console.log(data);


              const fileName = Constants.Image_URL + 'exportedExcel/' + data;

              const link = document.createElement('a');
              link.download = data + '.xls';
              const uri = fileName + '.xls';
              link.href = uri;
              document.body.appendChild(link);
              link.click();
              document.body.removeChild(link);
              $('#excelType').val('');
              this.selectedItems = [];

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
              this.spinner.hide();
            }
          );

        } else if ($('#excelType').val() == '7') {

              $.alert({
                title: 'Alert',
                content: 'There is no Employee in this Department! , Please Select anther Department',
              });



        } else if ($('#excelType').val() == '9') {

              $.alert({
                title: 'Alert',
                content: 'There is no Employee in this Department! , Please Select anther Department',
              });



        } else if ($('#excelType').val() == '8') {

              $.alert({
                title: 'Alert',
                content: 'There is no Employee in this Department! , Please Select anther Department',
              });



        }




      }
    }


  }

  total() {

  }

  // rerender(){
  //   this.dtElement.dtInstance.then((dtInstance: DataTables.Api) => {
  //
  //     dtInstance.destroy();
  //
  //     this.dtTrigger.next();
  //   });
  // }

  searchAttendance() {



    if (this.selectedItems.length <= 0) {
        $.alert({
          title: 'Alert',
          content: 'Please select an Employee',
        });
    } else if (this.selectedItems.length > 1) {

        $.alert({
          title: 'Alert',
          content: 'Please select only 1 Employee',
        });

    } else {
          const token = this.token.get();

          const empList = [];
          for (let $i = 0; $i < this.selectedItems.length; $i++) {
            empList.push(this.selectedItems[$i]['empid']);
          }

          this.http.post(Constants.API_URL + 'report/getEmployeeAttendance' + '?token=' + token, {
            startDate: $('#startDate').val(),
            endDate: $('#endDate').val(),
            empId: empList,

          }).subscribe(data => {

              this.spinner.hide();

              this.attendenceDataDates = data['dates'];
              this.attendenceDataResults = data['result'];
              this.attendenceDataAllEmp = data['allEmp'];
              console.log(this.attendenceDataResults);




            },
            error => {
              console.log(error);
              this.spinner.hide();
            }
          );

    }







  }

  getPreAndNextDate() {

   // console.log('test');

  }
  previusDay(date) {




     const d = new Date(date);
     const previousdate = d.getDate() - 1;
     let dd = '';
     let mm = '';



   const month = d.getMonth() + 1;

    if (previousdate < 10) {
       dd = '0' + previousdate;
    } else {
      dd = '' + previousdate;
    }
    if (month < 10) {
       mm = '0' + month;
    } else {
        mm = '' + month;
    }
    const yesterdayString = d.getFullYear() + '-' + mm + '-' + dd;

    return yesterdayString;

  }


}
