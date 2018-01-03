import { Injectable } from '@angular/core';
import { Http } from '@angular/http';

import { environment as env } from '../../environments/environment';
import { BaseController } from '../core/controller/base-controller';
import { PictureEntity } from '../entities/picture-entity';

@Injectable()
export class PictureDownController extends BaseController<PictureEntity> {
  constructor(http: Http) {
    super(http, `${env.apiUrl}/picture-down.php`);
  }

  post(args: { categoryId: string, pictureId: string }) {
    return super.post(args);
  }
}
