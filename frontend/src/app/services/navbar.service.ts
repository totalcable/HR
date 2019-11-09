import { Injectable } from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {Constants} from '../../app/constants';
import {TokenService} from "./token.service";

@Injectable({
  providedIn: 'root'
})
export class NavbarService {

    visible: boolean;

    constructor(public http: HttpClient,private token:TokenService) { this.visible = false; }

    hide() { this.visible = false; }

    show() { this.visible = true; }

    toggle() { this.visible = !this.visible; }



}
