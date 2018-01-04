import { Http, Response, URLSearchParams, RequestOptions } from '@angular/http';

import { BaseEntity } from '../../core/entity/base-entity';
import { AppError } from '../../core/exception/app-error';
import { SessionError } from '../../core/exception/session-error';
import { ClientException } from '../../core/exception/client-exception';

export abstract class BaseController<Type extends BaseEntity> {
  private _sessionErrorCode = 501;

  constructor(private _http: Http, private _url: string) { }

  get(args: { [key: string]: any } = {}): Promise<Type> {
    return this._send('get', args);
  }

  post(args: { [key: string]: any } = {}): Promise<Type> {
    return this._send('post', args);
  }


  private async _send(
    method: 'get' | 'post',
    args: { [key: string]: any } = {}
  ): Promise<Type> {
    // builds parameters
    const params = new URLSearchParams();
    for (const name in args) {
      if (args.hasOwnProperty(name)) {
        const value = args[name];
        if (value !== undefined && value !== null) {
          params.append(name, '' + value);
        }
      }
    }

    // prepares the request
    const options = new RequestOptions({ withCredentials: true });
    const request = method === 'get'
      ? this._http.get(this._url, options.merge({ params }))
      : this._http.post(this._url, params, options);

    // checkes and returns the response
    let reply = null;
    let doc = null;
    try {
      reply = await request.toPromise();
      doc = reply.json();
    } catch (e) {
      const errorDoc = e.json();
      const message = e.message ||
        'Unknown error.\nPlease contact technical support';

      // rethrows the exception with addition information
      const status = errorDoc.status;
      const statusCode = status ? errorDoc.status.code : 500;
      const statusMessage = status ? errorDoc.status.message : message;
      throw this._createException(statusCode, statusMessage);
    }

    return doc.body;
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
