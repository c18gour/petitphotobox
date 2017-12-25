import { Injectable } from '@angular/core';

import { environment as env } from '../../environments/environment';
import { BaseController } from '../core/service/controller/base-controller';
import { LogoutDocument } from '../documents/logout-document';
import { HttpRequest } from '../core/service/http/http-request';

@Injectable()
export class LogoutController extends BaseController {
  constructor(private _http: HttpRequest) {
    super(`${env.apiUrl}/user-logout.php`);
  }

  async get() {
    const response = await this._http.get(this.url);

    return new LogoutDocument(response.json());
  }

  async post() {
    const response = await this._http.post(this.url);

    return new LogoutDocument(response.json());
  }
}
