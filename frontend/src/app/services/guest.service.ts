import { Injectable } from '@angular/core';

import {ActivatedRouteSnapshot, CanActivate, Router, RouterStateSnapshot} from '@angular/router';
import {Observable} from 'rxjs';
import {TokenService} from './token.service';

@Injectable({
  providedIn: 'root'
})

export class GuestService implements CanActivate{

  constructor(private token:TokenService,private router:Router) { }

  canActivate(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): Observable<boolean> | Promise<boolean> | boolean {
    // return !this.token.isValid();
      if (!this.token.isValid()) {
          // all ok, proceed navigation to routed component
          return true;
      }
      else {
          // start a new navigation to redirect to login page
          this.router.navigate(['/home']);
          // abort current navigation
          return false;
      }
  }
}
