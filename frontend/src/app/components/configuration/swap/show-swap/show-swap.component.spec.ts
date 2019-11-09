import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ShowSwapComponent } from './show-swap.component';

describe('ShowSwapComponent', () => {
  let component: ShowSwapComponent;
  let fixture: ComponentFixture<ShowSwapComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ShowSwapComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ShowSwapComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
