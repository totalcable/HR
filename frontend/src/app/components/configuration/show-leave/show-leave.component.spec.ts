import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ShowLeaveComponent } from './show-leave.component';

describe('ShowLeaveComponent', () => {
  let component: ShowLeaveComponent;
  let fixture: ComponentFixture<ShowLeaveComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ShowLeaveComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ShowLeaveComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
