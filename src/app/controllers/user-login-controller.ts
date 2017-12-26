import { Injectable } from '@angular/core';
import { Http } from '@angular/http';

import { environment as env } from '../../environments/environment';
import { UserLoginEntity } from '../entities/user-login-entity';
import { BaseController } from '../core/controller/base-controller';

@Injectable()
export class UserLoginController extends BaseController<UserLoginEntity> {
  constructor(http: Http) {
    super(http, `${env.apiUrl}/user-login.php`);
  }

  // TODO: checks the response agains a JSON standard document
  post(args: { username: string, password: string }) {
    return super.post(args);
  }
}
