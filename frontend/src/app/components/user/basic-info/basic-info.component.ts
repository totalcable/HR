import {Component, OnInit, Input} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {Constants} from '../../../constants';
import {TokenService} from '../../../services/token.service';
import {Router} from '@angular/router';

declare var $: any;

@Component({
  selector: 'app-basic-info',
  templateUrl: './basic-info.component.html',
  styleUrls: ['./basic-info.component.css']
})
export class BasicInfoComponent implements OnInit {

  department: any;
  previousURL: any;
  designation: any;
  empType: any;
  basicinfo: any;
  result: any;
  error = [];
  employeeBasicForm: any = {
    id: '',

    firstName: '',
    middleName: '',
    lastName: '',

    email: '',
    streetAddress: '',
    apartmentUnit: '',
    city: '',
    state: '',
    zipCode: '',
    contactNo: '',
    homePhone: '',

    alterContactNo: '',
    birthdate: '',
    nationalId: '',
    gender: '',
    photo: ''

  };

  selectedFile: File;
  selectedCVFile: File;

  @Input('empid') empid: any;

  constructor(public http: HttpClient, private token: TokenService, private router: Router) { }

  ngOnInit() {

        this.getBasicInfo();
        this.previousURL = 'employee';

  }

  getBasicInfo() {

    const token = this.token.get();
    this.http.post(Constants.API_URL + 'employee/basicinfo' + '?token=' + token, { empid: this.empid}).subscribe(data => {

        // console.log(data);

        this.basicinfo  = data;

        if (data != null) {

          this.employeeBasicForm.id = this.empid;
          this.employeeBasicForm.EmployeeId = this.basicinfo.EmployeeId;
          this.employeeBasicForm.firstName = this.basicinfo.firstName;
          this.employeeBasicForm.middleName = this.basicinfo.middleName;
          this.employeeBasicForm.lastName = this.basicinfo.lastName;
          this.employeeBasicForm.email = this.basicinfo.email;
          this.employeeBasicForm.gender = this.basicinfo.gender;
          this.employeeBasicForm.birthdate = this.basicinfo.birthdate;


          this.employeeBasicForm.streetAddress = this.basicinfo.streetAddress;
          this.employeeBasicForm.apartmentUnit = this.basicinfo.apartmentUnit;
          this.employeeBasicForm.city = this.basicinfo.city;
          this.employeeBasicForm.state = this.basicinfo.state;
          this.employeeBasicForm.zipCode = this.basicinfo.zipCode;
          this.employeeBasicForm.homePhone = this.basicinfo.homePhone;
          this.employeeBasicForm.maritalStatus = this.basicinfo.maritalStatus;
          this.employeeBasicForm.nationalId = this.basicinfo.nationalId;
          this.employeeBasicForm.alterContactNo = this.basicinfo.alterContactNo;
          this.employeeBasicForm.photo = this.basicinfo.photo;
          this.employeeBasicForm.photoLink = Constants.Image_URL + 'images/' + this.basicinfo.photo;
          this.employeeBasicForm.cvLink = Constants.Image_URL + 'cv/' + this.basicinfo.cv;
          this.employeeBasicForm.cv = this.basicinfo.cv;
          // console.log(this.employeeBasicForm.photo);
        }
      },
      error => {
        console.log(error);
      }
    );

  }

  cvDelete() {

    const token = this.token.get();


    this.http.post(Constants.API_URL + 'employee/cvDelete' + '?token=' + token, { empid: this.empid}).subscribe(data => {

        $.alert({
          title: 'Success!',
          type: 'Green',
          content: 'CV file deleted Successfully',
          buttons: {
            tryAgain: {
              text: 'Ok',
              btnClass: 'btn-green',
              action: function () {
              }
            }
          }
        });

      this.getBasicInfo();
        $('#cv').val('');

      },
      error => {
        console.log(error);
      }
    );

  }
  imageDelete() {

    const token = this.token.get();


    this.http.post(Constants.API_URL + 'employee/imageDelete' + '?token=' + token, { empid: this.empid}).subscribe(data => {

        $.alert({
          title: 'Success!',
          type: 'Green',
          content: 'Image deleted Successfully',
          buttons: {
            tryAgain: {
              text: 'Ok',
              btnClass: 'btn-green',
              action: function () {
              }
            }
          }
        });

      this.getBasicInfo();
        $('#photo').val('');

      },
      error => {
        console.log(error);
      }
    );

  }




  onFileSelected(event) {

    this.selectedFile = event.target.files[0];

  }
  onCVFileSelected(event) {

    this.selectedCVFile = event.target.files[0];

  }
  checkRequiredFields() {
    // if(this.employeeBasicForm.EmployeeId == ''){
    //   return false;
    // }
    if (this.employeeBasicForm.firstName == '') {
      return false;
    }
    if (  this.employeeBasicForm.lastName == '') {
      return false;
    }
    if (this.employeeBasicForm.gender == '' || this.employeeBasicForm.gender == null) {
      return false;
    }
    if (this.employeeBasicForm.birthdate == '' || this.employeeBasicForm.birthdate == null ) {
      return false;
    }
    // if(this.employeeBasicForm.department == ''){
    //   return false;
    // }
    // if(this.employeeBasicForm.empType == ''){
    //   return false;
    // }
    // if(this.employeeBasicForm.designation == ''){
    //   return false;
    // }

    if (this.employeeBasicForm.email == '') {
      return false;
    }
    return true;
  }

  onSubmit() {
    if (!this.checkRequiredFields()) {
      $.alert({
        title: 'Alert!',
        type: 'Red',
        content: 'Please Insert Mandatory Fields',
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

    // console.log(this.employeeBasicForm.gender);


    const fd = new FormData();
    const value = this.employeeBasicForm;
    for ( const key in value ) {
      fd.append(key, value[key]);
    }

    if (this.selectedFile) {

      fd.append('photo', this.selectedFile, this.selectedFile.name);
    }
    if (this.selectedCVFile) {

      fd.append('cv', this.selectedCVFile, this.selectedCVFile.name);
    }


    const token = this.token.get();

    this.http.post(Constants.API_URL + 'employee/storeBasicInfo' + '?token=' + token, fd).subscribe(data => {



        this.basicinfo = data;

        $.alert({
          title: 'Success!',
          type: 'Green',
          content: 'Employee Updated Successfully',
          buttons: {
            tryAgain: {
              text: 'Ok',
              btnClass: 'btn-red',
              action: function () {
              }
            }
          }
        });

        this.getBasicInfo();
        $('#photo').val('');
        $('#cv').val('');



      },
      error => {
        const data = error.error.errors;
        for (const p in data) {
          for (const k in data[p]) {
            this.error.push(data[p][k]);
          }
        }

      }
    );
  }

}
