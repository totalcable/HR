import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { RequestSwapComponent } from './request-swap.component';

describe('RequestSwapComponent', () => {
  let component: RequestSwapComponent;
  let fixture: ComponentFixture<RequestSwapComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ RequestSwapComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(RequestSwapComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
