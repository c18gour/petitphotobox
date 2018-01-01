import { Injectable } from '@angular/core';
import { Http } from '@angular/http';

import { environment as env } from '../../environments/environment';
import { BaseController } from '../core/controller/base-controller';
import { CategoryEntity } from '../entities/category-entity';

@Injectable()
export class CategoryNewController extends BaseController<CategoryEntity> {
  constructor(http: Http) {
    super(http, `${env.apiUrl}/category-new.php`);
  }

  get(args?: { parentCategoryId: string }) {
    return super.get(args);
  }

  // TODO: remove categoryId parameter
  post(args: { parentCategoryId: string, title: string }) {
    return super.post(args);
  }
}
