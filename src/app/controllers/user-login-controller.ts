import { Injectable } from '@angular/core';

import { environment as env } from '../../environments/environment';
import { UserLoginDocument } from '../documents/user-login-document';
import { BaseController } from '../core/service/controller/base-controller';
import { HttpRequest } from '../core/service/http/http-request';

@Injectable()
export class UserLoginController extends BaseController {
  constructor(private _http: HttpRequest) {
    super(`${env.apiUrl}/user-login.php`);
  }

  // TODO: checks the response agains a JSON standard document
  async get() {
    const response = await this._http.get(this.url);

    return new UserLoginDocument(response.json());
  }

  async post(username: string, password: string) {
    const response = await this._http.post(this.url, { username, password });

    return new UserLoginDocument(response.json());
  }
}
