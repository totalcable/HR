import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { EditAssignedShiftComponent } from './edit-assigned-shift.component';

describe('EditAssignedShiftComponent', () => {
  let component: EditAssignedShiftComponent;
  let fixture: ComponentFixture<EditAssignedShiftComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ EditAssignedShiftComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(EditAssignedShiftComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
