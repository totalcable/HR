import {Component, OnInit, AfterViewInit, Renderer, OnDestroy, ViewChild} from '@angular/core';
import {Constants} from '../../../constants';
import {HttpClient} from '@angular/common/http';
import {TokenService} from '../../../services/token.service';
import {Subject} from 'rxjs';
import {ActivatedRoute, Router} from '@angular/router';
import {DataTableDirective} from 'angular-datatables';
import {NgbModal} from '@ng-bootstrap/ng-bootstrap';
import {NgxSpinnerService} from 'ngx-spinner';

declare var $: any;

@Component({
  selector: 'app-database',
  templateUrl: './database.component.html',
  styleUrls: ['./database.component.css']
})
export class DatabaseComponent implements OnInit {

  constructor(private modalService: NgbModal, private renderer: Renderer, public http: HttpClient, private token: TokenService ,
              public route: ActivatedRoute, private router: Router, private spinner: NgxSpinnerService) { }

  ngOnInit() {
    this.databaseDownload();
  }

  databaseDownload() {

    const token = this.token.get();

    this.spinner.show();
    this.http.get(Constants.API_URL + 'database/backup' + '?token=' + token).subscribe(data => {

        this.spinner.hide();
        // console.log(data);

        const fileName = Constants.Image_URL + 'DBbackup/' + data['fileName'];

        const link = document.createElement('a');

        link.download = data['fileName'] + '.sql';
        const uri = fileName + '.sql';
        link.href = uri;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

      },
      error => {
        console.log(error);
      }
    );

  }

}
