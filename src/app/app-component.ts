import { Component } from '@angular/core';
import { CookieService } from 'ngx-cookie-service';
import { TranslateService } from '@ngx-translate/core';
import { environment as env } from '../environments/environment';

@Component({
  selector: 'app-root',
  templateUrl: './app-component.html',
  styleUrls: ['./app-component.scss']
})
export class AppComponent {
  title = 'app';

  constructor(
    private _translate: TranslateService,
    private _cookie: CookieService
  ) {
    _translate.setDefaultLang(env.defaultLanguage);

    const language = _cookie.get('lang');
    if (language) {
      _translate.use(language);
    }
  }
}
