import { Injectable } from '@angular/core';
import { Http } from '@angular/http';

import { environment as env } from '../../../../environments/environment';
import { BaseController } from '../../../core/controller/base-controller';
import { UserRegisterCompleteEntity } from '../entities/user-register-complete-entity';

@Injectable()
export class UserRegisterCompleteController extends BaseController<UserRegisterCompleteEntity> {
  constructor(http: Http) {
    super(http, `${env.apiUrl}/user-register-complete.php`);
  }

  post(args: { name: string, language: string }) {
    return super.post(args);
  }
}
