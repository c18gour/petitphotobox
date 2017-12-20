import { Injectable } from '@angular/core';
import { Http, URLSearchParams } from '@angular/http';

import { environment as env } from '../../environments/environment';
import { BaseController } from '../core/controller/base-controller';
import { LoginDocument } from '../model/documents/login-document';

@Injectable()
export class LoginController extends BaseController {

  constructor(private _http: Http) {
    super(`${env.apiUrl}/user-login.php`);
  }

  // TODO: checks the response agains a JSON standard document
  async get() {
    const response = await this._http.get(this.url).toPromise();

    return new LoginDocument(response.json());
  }

  async post(doc: LoginDocument) {
    const response = await this._http
      .post(this.url, JSON.stringify(doc)).toPromise();

    return new LoginDocument(response.json());
  }
}
