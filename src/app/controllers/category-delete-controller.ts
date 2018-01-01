import { Injectable } from '@angular/core';
import { Http } from '@angular/http';

import { environment as env } from '../../environments/environment';
import { BaseController } from '../core/controller/base-controller';
import { CategoryEntity } from '../entities/category-entity';

@Injectable()
export class CategoryDeleteController extends BaseController<CategoryEntity> {
  constructor(http: Http) {
    super(http, `${env.apiUrl}/category-delete.php`);
  }

  get(args?: { categoryId: string }) {
    return super.get(args);
  }

  post(args: { categoryId: string }) {
    return super.post(args);
  }
}
