import { Injectable } from '@angular/core';
import { Http } from '@angular/http';

import { environment as env } from '../../../../environments/environment';
import { BaseController } from '../../../core/controller/base-controller';
import { PictureEditEntity } from '../entities/picture-edit-entity';

@Injectable()
export class PictureEditController extends BaseController<PictureEditEntity> {
  constructor(http: Http) {
    super(http, `${env.apiUrl}/picture-edit.php`);
  }

  get(args?: { pictureId: string }) {
    return super.get(args);
  }

  post(args: { pictureId: string, categoryIds: string[], title?: string }) {
    return super.post(args);
  }
}
