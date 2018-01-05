import { Injectable } from '@angular/core';
import { Http } from '@angular/http';

import { environment as env } from '../../../../environments/environment';
import { BaseController } from '../../../core/controller/base-controller';
import { UserLoginEntity } from '../entities/user-login-entity';

@Injectable()
export class UserLoginController extends BaseController<UserLoginEntity> {
  constructor(http: Http) {
    super(http, `${env.apiUrl}/user-login.php`);
  }

  post(args: { username: string, password: string }) {
    return super.post(args);
  }
}
