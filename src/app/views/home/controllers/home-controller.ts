import { Injectable } from '@angular/core';
import { Http } from '@angular/http';

import { environment as env } from '../../../../environments/environment';
import { BaseController } from '../../../core/controller/base-controller';
import { HomeEntity } from '../entities/home-entity';

@Injectable()
export class HomeController extends BaseController<HomeEntity> {
  constructor(http: Http) {
    super(http, `${env.apiUrl}/home.php`);
  }

  get(args: { categoryId: string, page?: number }) {
    return super.get(args);
  }
}
