import { Injectable } from '@angular/core';
import { Http, URLSearchParams } from '@angular/http';

import { environment as env } from '../../environments/environment';
import { BaseController } from '../core/controller/base-controller';
import { LoginModel } from '../models/login-model';

@Injectable()
export class LoginController extends BaseController {

  constructor(private _http: Http) {
    super(`${env.apiUrl}/user-login.php`);
  }

  // TODO: checks the response agains a JSON standard document
  async get() {
    const response = await this._http.get(this.url).toPromise();

    return new LoginModel(response.json());
  }

  async post(model: LoginModel) {
    const params = new URLSearchParams();
    params.append('username', model.username);
    params.append('password', model.password);

    const response = await this._http
      .post(this.url, params.toString()).toPromise();

    return new LoginModel(response.json());
  }
}
