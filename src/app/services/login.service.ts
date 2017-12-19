import { Injectable } from '@angular/core';
import { Http, Headers, ResponseContentType } from '@angular/http';

import { environment as env } from '../../environments/environment';

@Injectable()
export class LoginService {
  private _url = `${env.apiUrl}/user-login.php`;

  constructor(private _http: Http) { }

  async post(username: string, password) {
    // TODO: throw an exception if the response is not a JSON object
    return (await this._http.get(this._url).toPromise()).json();
  }
}
