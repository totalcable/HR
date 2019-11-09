import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { NotShiftAssignListComponent } from './not-shift-assign-list.component';

describe('NotShiftAssignListComponent', () => {
  let component: NotShiftAssignListComponent;
  let fixture: ComponentFixture<NotShiftAssignListComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ NotShiftAssignListComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(NotShiftAssignListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
