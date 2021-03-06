import { Injectable } from '@angular/core';
import { Http } from '@angular/http';

import { environment as env } from '../../../../environments/environment';
import { BaseController } from '../../../core/controller/base-controller';

@Injectable()
export class CategoryPictureDeleteController
  extends BaseController {
  constructor(http: Http) {
    super(http, `${env.apiUrl}/category-picture-delete.php`);
  }

  post(args: { categoryId: string, pictureId: string }) {
    return super.post(args);
  }
}
