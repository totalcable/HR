import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { GovmentHolidayComponent } from './govment-holiday.component';

describe('GovmentHolidayComponent', () => {
  let component: GovmentHolidayComponent;
  let fixture: ComponentFixture<GovmentHolidayComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ GovmentHolidayComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(GovmentHolidayComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
