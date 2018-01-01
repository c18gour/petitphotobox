import { Injectable } from '@angular/core';
import { Http } from '@angular/http';

import { environment as env } from '../../environments/environment';
import { BaseController } from '../core/controller/base-controller';
import { CategoryEntity } from '../entities/category-entity';

@Injectable()
export class CategoryEditController extends BaseController<CategoryEntity> {
  constructor(http: Http) {
    super(http, `${env.apiUrl}/category-edit.php`);
  }

  get(args?: { categoryId: string }) {
    return super.get(args);
  }

  post(args: { parentCategoryId: string, categoryId: string, title: string }) {
    return super.post(args);
  }
}
