import { Injectable } from '@angular/core';
import { Http, Response, URLSearchParams, RequestOptions } from '@angular/http';

import { AppError } from '../../../core/exception/app-error';
import { SessionError } from '../../../core/exception/session-error';
import { ClientException } from '../../../core/exception/client-exception';

@Injectable()
export class HttpRequest {
  private _sessionErrorCode = 501;

  constructor(private _http: Http) { }

  get(url: string, args: { [key: string]: any } = {}): Promise<Response> {
    return this._send('get', url, args);
  }

  post(url: string, args: { [key: string]: any } = {}): Promise<Response> {
    return this._send('post', url, args);
  }

  private async _send(
    method: 'get' | 'post',
    url: string,
    args: { [key: string]: any } = {}): Promise<Response> {

    // builds parameters
    const params = new URLSearchParams();
    for (const name in args) {
      if (args.hasOwnProperty(name)) {
        params.append(name, '' + args[name]);
      }
    }

    // prepares the request
    const request = method === 'get'
      ? this._http.get(url, new RequestOptions({ params }))
      : this._http.post(url, params);

    // checkes and returns the response
    try {
      return await request.toPromise();
    } catch (e) {
      const doc = e.json();
      const statusCode = doc.status.code;
      const statusMessage = doc.status.message;

      throw this._createException(statusCode, statusMessage);
    }
  }

  private _createException(code: number, message: string) {
    if (code > 399 && code < 500) {
      // client exception (4xx)
      throw new ClientException(message);
    } else if (code > 499 && code < 600) {
      // app error (5xx)
      throw code === this._sessionErrorCode
        ? new SessionError(message)
        : new AppError(message);
    } else {
      // generic error
      throw Error(message);
    }
  }
}
