import { Injectable } from '@angular/core';
import { Http } from '@angular/http';

import { environment as env } from '../../../../environments/environment';
import { BaseController } from '../../../core/controller/base-controller';
import { UserSettingsEntity } from '../entities/user-settings-entity';

@Injectable()
export class UserSettingsController extends BaseController<UserSettingsEntity> {
  constructor(http: Http) {
    super(http, `${env.apiUrl}/user-settings.php`);
  }

  post(args: { name: string, language: string }) {
    return super.post(args);
  }
}
