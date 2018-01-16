import { Injectable } from '@angular/core';
import { Http } from '@angular/http';

import { environment as env } from '../../../../environments/environment';
import { BaseController } from '../../../core/controller/base-controller';

@Injectable()
export class PictureDeleteController extends BaseController {
  constructor(http: Http) {
    super(http, `${env.apiUrl}/picture-delete.php`);
  }

  post(args: { pictureId: string }) {
    return super.post(args);
  }
}
