import { Injectable } from '@angular/core';
import { Http } from '@angular/http';

import { environment as env } from '../../environments/environment';
import { BaseController } from '../core/service/controller/base-controller';
import { HomeEntity } from '../model/entities/home-entity';

@Injectable()
export class HomeController extends BaseController<HomeEntity> {
  constructor(http: Http) {
    super(http, `${env.apiUrl}/home.php`);
  }

  get(args?: { category_id: string }) {
    return super.get(args);
  }
}
