import { AppError } from './app-error';
import { SessionError } from './session-error';
import { ClientException } from './client-exception';

export class ExceptionFactory {
  static create(code: number, message: string) {
    let ret = null;

    if (code > 399 && code < 500) {
      // client exception (4xx)
      ret = new ClientException(message);
    } else if (code > 499 && code < 600) {
      // app error (5xx)
      // QUESTION: create a list of error codes?
      ret = code === 501 ? new SessionError(message) : new AppError(message);
    } else {
      // generic error
      ret = new Error(message);
    }

    return ret;
  }
}
