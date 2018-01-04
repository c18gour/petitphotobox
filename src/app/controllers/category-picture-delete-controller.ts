import { Injectable } from '@angular/core';
import { Http } from '@angular/http';

import { environment as env } from '../../environments/environment';
import { BaseController } from '../core/controller/base-controller';
import { PictureEntity } from '../entities/picture-entity';

@Injectable()
export class CategoryPictureDeleteController
  extends BaseController<PictureEntity> {
  constructor(http: Http) {
    super(http, `${env.apiUrl}/category-picture-delete.php`);
  }

  post(args: { id: string }) {
    return super.post(args);
  }
}