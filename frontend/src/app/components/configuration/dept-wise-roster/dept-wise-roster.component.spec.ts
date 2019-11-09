import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DeptWiseRosterComponent } from './dept-wise-roster.component';

describe('DeptWiseRosterComponent', () => {
  let component: DeptWiseRosterComponent;
  let fixture: ComponentFixture<DeptWiseRosterComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DeptWiseRosterComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DeptWiseRosterComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
