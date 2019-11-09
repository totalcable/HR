import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { LeaveSummeryComponent } from './leave-summery.component';

describe('LeaveSummeryComponent', () => {
  let component: LeaveSummeryComponent;
  let fixture: ComponentFixture<LeaveSummeryComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ LeaveSummeryComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(LeaveSummeryComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
