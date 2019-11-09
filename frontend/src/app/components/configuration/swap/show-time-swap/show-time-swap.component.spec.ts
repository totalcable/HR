import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ShowTimeSwapComponent } from './show-time-swap.component';

describe('ShowTimeSwapComponent', () => {
  let component: ShowTimeSwapComponent;
  let fixture: ComponentFixture<ShowTimeSwapComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ShowTimeSwapComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ShowTimeSwapComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
