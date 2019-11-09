import { TestBed, inject } from '@angular/core/testing';

import { RoleManagementService } from './role-management.service';

describe('RoleManagementService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [RoleManagementService]
    });
  });

  it('should be created', inject([RoleManagementService], (service: RoleManagementService) => {
    expect(service).toBeTruthy();
  }));
});
