import { Injectable } from '@angular/core';
import { Http } from '@angular/http';

import { environment as env } from '../../../../environments/environment';
import { BaseController } from '../../../core/controller/base-controller';

@Injectable()
export class UserRegisterController extends BaseController {
  constructor(http: Http) {
    super(http, `${env.apiUrl}/user-register.php`);
  }

  get(args: { code: string, state: string }) {
    return super.get(args);
  }
}
