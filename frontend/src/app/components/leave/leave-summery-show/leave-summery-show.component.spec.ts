import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { LeaveSummeryShowComponent } from './leave-summery-show.component';

describe('LeaveSummeryShowComponent', () => {
  let component: LeaveSummeryShowComponent;
  let fixture: ComponentFixture<LeaveSummeryShowComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ LeaveSummeryShowComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(LeaveSummeryShowComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
