import { Injectable } from '@angular/core';
import { Http, URLSearchParams } from '@angular/http';

import { environment as env } from '../../environments/environment';
import { BaseController } from '../core/controller/base-controller';
import { UserLoginDocument } from '../documents/user-login-document';

@Injectable()
export class UserLoginController extends BaseController {

  constructor(private _http: Http) {
    super(`${env.apiUrl}/user-login.php`);
  }

  // TODO: checks the response agains a JSON standard document
  async get() {
    const response = await this._http.get(this.url).toPromise();

    return new UserLoginDocument(response.json());
  }

  async post(username: string, password: string) {
    const params = new URLSearchParams();
    params.append('username', username);
    params.append('password', password);

    const response = await this._http.post(this.url, params).toPromise();

    return new UserLoginDocument(response.json());
  }
}
