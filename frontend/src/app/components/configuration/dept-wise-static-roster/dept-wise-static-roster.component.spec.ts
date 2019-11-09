import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DeptWiseStaticRosterComponent } from './dept-wise-static-roster.component';

describe('DeptWiseStaticRosterComponent', () => {
  let component: DeptWiseStaticRosterComponent;
  let fixture: ComponentFixture<DeptWiseStaticRosterComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DeptWiseStaticRosterComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DeptWiseStaticRosterComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
