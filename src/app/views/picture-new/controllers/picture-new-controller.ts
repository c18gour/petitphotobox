import { Injectable } from '@angular/core';
import { Http } from '@angular/http';

import { environment as env } from '../../../../environments/environment';
import { BaseController } from '../../../core/controller/base-controller';
import { PictureNewEntity } from '../entities/picture-new-entity';

@Injectable()
export class PictureNewController extends BaseController<PictureNewEntity> {
  constructor(http: Http) {
    super(http, `${env.apiUrl}/picture-new.php`);
  }

  get(args: { categoryIds: string[] }) {
    return super.get(args);
  }

  post(
    args: {
      categoryIds?: string[],
      title?: string,
      tags?: string,
      snapshots: string[]
    }
  ) {
    return super.post(args);
  }
}
