import { Injectable } from '@angular/core';

import { environment as env } from '../../environments/environment';
import { BaseController } from '../core/controller/base-controller';
import { UserLoginDocument } from '../documents/user-login-document';

import { HttpRequest } from '../core/http/http-request';

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
