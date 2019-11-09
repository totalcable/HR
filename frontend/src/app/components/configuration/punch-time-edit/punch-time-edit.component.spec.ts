import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PunchTimeEditComponent } from './punch-time-edit.component';

describe('PunchTimeEditComponent', () => {
  let component: PunchTimeEditComponent;
  let fixture: ComponentFixture<PunchTimeEditComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PunchTimeEditComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PunchTimeEditComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
