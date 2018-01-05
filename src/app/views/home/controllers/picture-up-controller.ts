import { Injectable } from '@angular/core';
import { Http } from '@angular/http';

import { environment as env } from '../../../../environments/environment';
import { BaseController } from '../../../core/controller/base-controller';

@Injectable()
export class PictureUpController extends BaseController {
  constructor(http: Http) {
    super(http, `${env.apiUrl}/picture-up.php`);
  }

  post(args: { id: string }) {
    return super.post(args);
  }
}
