import { Injectable } from '@angular/core';
import { Http } from '@angular/http';

import { environment as env } from '../../../../environments/environment';
import { BaseController } from '../../../core/controller/base-controller';
import { CategoryEditEntity } from '../entities/category-edit-entity';

@Injectable()
export class CategoryEditController extends BaseController<CategoryEditEntity> {
  constructor(http: Http) {
    super(http, `${env.apiUrl}/category-edit.php`);
  }

  // TODO: args is required
  get(args?: { categoryId: string }) {
    return super.get(args);
  }

  post(args: { parentCategoryId: string, categoryId: string, title: string }) {
    return super.post(args);
  }
}
