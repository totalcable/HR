import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
// import { RouterModule, Routes } from '@angular/router';
import { ActivatedRouteSnapshot, RouterModule, RouterStateSnapshot, Routes } from '@angular/router';

import {HomeComponent} from './components/home/home.component';
import {LoginComponent} from './components/login/login.component';
import {GuestService} from './services/guest.service';
import {AuthService} from './services/auth.service';
import { NgxPermissionsGuard } from 'ngx-permissions';
import {EmployeeComponent} from './components/user/employee/employee.component';
import {AddDepartmentComponent} from './components/configuration/department/add-department/add-department.component';
import {AddEmailComponent} from './components/configuration/email/add-email/add-email.component';
import {ShiftComponent} from './components/configuration/shift/shift.component';
import {ShiftAssignComponent} from './components/configuration/shift-assign/shift-assign.component';
import {EditAssignedShiftComponent} from './components/configuration/edit-assigned-shift/edit-assigned-shift.component';
import {AttendanceComponent} from './components/report/attendance/attendance.component';
import {AddEmployeeComponent} from './components/user/add-employee/add-employee.component';
import {AddLeaveComponent} from './components/leave/add-leave/add-leave.component';
import {LeaveComponent} from './components/configuration/leave/leave.component';
import { NotShiftAssignListComponent } from './components/configuration/not-shift-assign-list/not-shift-assign-list.component';
import { LeaveSummeryShowComponent } from './components/leave/leave-summery-show/leave-summery-show.component';
import {LeaveSummeryComponent} from './components/leave/leave-summery/leave-summery.component';
import {ShowLeaveComponent} from './components/configuration/show-leave/show-leave.component';
import { AddDesignationComponent } from './components/configuration/designation/add-designation/add-designation.component';
import { GovmentHolidayComponent } from './components/configuration/govment-holiday/govment-holiday.component';
import { ExtraWorkHistoryComponent } from './components/report/extra-work-history/extra-work-history.component';
import { ShowSwapComponent } from './components/configuration/swap/show-swap/show-swap.component';
import { RequestSwapComponent } from './components/configuration/swap/request-swap/request-swap.component';
import { ShowTimeSwapComponent } from './components/configuration/swap/show-time-swap/show-time-swap.component';
import { DeptWiseRosterComponent } from './components/configuration/dept-wise-roster/dept-wise-roster.component';
import { DeptWiseStaticRosterComponent } from './components/configuration/dept-wise-static-roster/dept-wise-static-roster.component';
import { PasswordChangeComponent } from './components/configuration/password-change/password-change.component';
import { PunchTimeEditComponent } from './components/configuration/punch-time-edit/punch-time-edit.component';
import { DatabaseComponent } from './components/configuration/database/database.component';



export function testPermissions(route: ActivatedRouteSnapshot, state: RouterStateSnapshot) {

    // console.log(route.params);
    // if (route.params['id'] === 42) {
    //     return ['MANAGER', "UTILS"]
    // } else {
    //     return 'ADMIN'


}
const routes: Routes = [
    {path: '', component: LoginComponent, canActivate: [GuestService] },
    { path: 'login', component: LoginComponent, canActivate: [GuestService] },
    { path: 'home', component: HomeComponent, canActivate: [AuthService] },
    { path: 'employee', component: EmployeeComponent, canActivate: [AuthService] },
    { path: 'employee/add', component: AddEmployeeComponent, canActivate: [AuthService] },
    { path: 'employee/edit/:id', component: AddEmployeeComponent, canActivate: [AuthService] },
    { path: 'configuration/department/add', component: AddDepartmentComponent, canActivate: [AuthService]  },
  // Email
    { path: 'configuration/email/add', component: AddEmailComponent, canActivate: [AuthService]  },
    { path: 'configuration/shift', component: ShiftComponent, canActivate: [AuthService] },
    { path: 'configuration/shift/assign', component: ShiftAssignComponent, canActivate: [AuthService] },
    { path: 'configuration/shift/edit-assign', component: EditAssignedShiftComponent, canActivate: [AuthService] },
    { path: 'report/attendance', component: AttendanceComponent, canActivate: [AuthService]  },
    { path: 'configuration/leave', component: LeaveComponent, canActivate: [AuthService]  },
    { path: 'leave/apply', component: AddLeaveComponent , canActivate: [AuthService] },
    { path: 'configuration/not-shift-assigned-list', component: NotShiftAssignListComponent , canActivate: [AuthService] },
    { path: 'configuration/not-shift-assigned-list/notAssignedinfo', component: NotShiftAssignListComponent , canActivate: [AuthService] },
    { path: 'notAssignedinfoPerEmp/:id/:userId/:start/:end', component: EditAssignedShiftComponent , canActivate: [AuthService]},
    { path: 'notAssignedinfoPerEmp/:id/:userId/:start/:end', component: EditAssignedShiftComponent , canActivate: [AuthService]},
    { path: 'leave/summery', component: LeaveSummeryComponent, canActivate: [AuthService]  },
    { path: 'leave/summery/:id', component: LeaveSummeryShowComponent, canActivate: [AuthService] },
    { path: 'configuration/leave/show', component: ShowLeaveComponent, canActivate: [AuthService]  },
    { path: 'configuration/designation/add', component: AddDesignationComponent, canActivate: [AuthService]  },
    { path: 'configuration/govtHoliday/show', component: GovmentHolidayComponent, canActivate: [AuthService]  },
    { path: 'adjustment/showAdjustment', component: ExtraWorkHistoryComponent, canActivate: [AuthService]  },
    { path: 'swap/showSwap', component: ShowSwapComponent, canActivate: [AuthService]  },
    { path: 'swap/RequestSwap', component: RequestSwapComponent, canActivate: [AuthService]  },
    { path: 'swap/showTimeSwap', component: ShowTimeSwapComponent, canActivate: [AuthService]  },
    { path: 'configuration/roster/dept-wise-assign', component: DeptWiseRosterComponent, canActivate: [AuthService]  },
    { path: 'configuration/roster/dept-wise-static-roster', component: DeptWiseStaticRosterComponent, canActivate: [AuthService]  },
    { path: 'configuration/employee/passwordChange', component: PasswordChangeComponent, canActivate: [AuthService]  },
    { path: 'configuration/punch-time/edit', component: PunchTimeEditComponent, canActivate: [AuthService]  },






];



@NgModule({
    imports: [
        RouterModule.forRoot(routes)
    ],
    exports: [
        RouterModule
    ],
    providers: [
        // CanDeactivateGuard
    ]
})


export class AppRoutingModule {


}
