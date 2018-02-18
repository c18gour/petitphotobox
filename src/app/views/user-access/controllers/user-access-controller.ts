import { Injectable } from '@angular/core';
import { Http } from '@angular/http';

import { environment as env } from '../../../../environments/environment';
import { BaseController } from '../../../core/controller/base-controller';
import { UserAccessEntity } from '../entities/user-access-entity';

@Injectable()
export class UserAccessController extends BaseController<UserAccessEntity> {
  constructor(http: Http) {
    super(http, `${env.apiUrl}/user-access.php`);
  }
}
