import { Injectable } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';

@Injectable()
export class AppTranslateService {
  constructor(private _translate: TranslateService) { }

  get(key: string) {
    return this._translate.get(key).toPromise<string>();
  }
}
