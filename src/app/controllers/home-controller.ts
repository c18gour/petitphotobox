import { Injectable } from '@angular/core';

import { environment as env } from '../../environments/environment';
import { BaseController } from '../core/service/controller/base-controller';
import { HomeDocument } from '../documents/home-document';
import { HttpRequest } from '../core/service/http/http-request';

@Injectable()
export class HomeController extends BaseController {
  constructor(private _http: HttpRequest) {
    super(`${env.apiUrl}/home.php`);
  }

  async get(categoryId?: string) {
    const response = await this._http.get(this.url, { categoryId });

    return new HomeDocument(response.json());
  }

  async post(categoryId: string) {
    const response = await this._http.post(
      this.url, { category_id: categoryId });

    return new HomeDocument(response.json());
  }
}
