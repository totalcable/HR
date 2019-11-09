import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { JoiningInfoComponent } from './joining-info.component';

describe('JoiningInfoComponent', () => {
  let component: JoiningInfoComponent;
  let fixture: ComponentFixture<JoiningInfoComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ JoiningInfoComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(JoiningInfoComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
