import {Component, OnInit, Input} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {Constants} from '../../../../constants';
import {TokenService} from '../../../../services/token.service';
import {Email} from '../../../../model/email.model';
declare var $: any;
@Component({
  selector: 'app-add-email',
  templateUrl: './add-email.component.html',
  styleUrls: ['./add-email.component.css']
})
export class AddEmailComponent implements OnInit {
  @Input('master') data: any;

  email: any;
  emails: any;
  emailField = {} as Email;
  status: any;

  constructor(public http: HttpClient, private token: TokenService) { }

  ngOnInit() {
    this.getEmails();
   // this.getDepartmentLevels();
    // this.getCompany();
  }

  getEmails() {
    const token = this.token.get();
    this.http.get(Constants.API_URL + 'email/get' + '?token=' + token).subscribe(data => {
        this.emails = data;
        console.log(this.emails);
      },
      error => {
        console.log(error);
      }
    );
  }


  editEmail(email) {
    this.emailField = email;
  }

  checkId() {
    if (Object.keys(this.emailField).length === 0) {
      return true;
    } else {
      if (this.emailField.id == null) {
        return true;
      }
      return false;
    }
  }
  onSubmit() {
    const token = this.token.get();
    this.http.post(Constants.API_URL + 'email/post' + '?token=' + token, this.emailField).subscribe(data => {
        this.getEmails();
        this.emailField = {} as Email;
        $.alert({
          title: 'Success!',
          type: 'Green',
          content: data['message'],
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
  reset() {
    this.emailField = {} as Email;
  }
}
