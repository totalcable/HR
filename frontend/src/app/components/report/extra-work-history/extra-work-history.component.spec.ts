import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ExtraWorkHistoryComponent } from './extra-work-history.component';

describe('ExtraWorkHistoryComponent', () => {
  let component: ExtraWorkHistoryComponent;
  let fixture: ComponentFixture<ExtraWorkHistoryComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ExtraWorkHistoryComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ExtraWorkHistoryComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
