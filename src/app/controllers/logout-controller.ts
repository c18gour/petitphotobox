import { Injectable } from '@angular/core';
import { Http } from '@angular/http';

import { environment as env } from '../../environments/environment';
import { BaseController } from '../core/service/controller/base-controller';
import { LogoutEntity } from '../model/entities/logout-entity';

@Injectable()
export class LogoutController extends BaseController<LogoutEntity> {
  constructor(http: Http) {
    super(http, `${env.apiUrl}/user-logout.php`);
  }

  post() {
    return super.post();
  }
}
