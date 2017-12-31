import { Injectable } from '@angular/core';
import { Http } from '@angular/http';

import { environment as env } from '../../environments/environment';
import { BaseController } from '../core/controller/base-controller';
import { CategoryNewEntity } from '../entities/category-new-entity';

@Injectable()
export class CategoryNewController extends BaseController<CategoryNewEntity> {
  constructor(http: Http) {
    super(http, `${env.apiUrl}/category-new.php`);
  }

  get(args?: { parentCategoryId: string }) {
    return super.get(args);
  }

  post(args: { parentCategoryId: string, categoryId: string, title: string }) {
    return super.post(args);
  }
}
