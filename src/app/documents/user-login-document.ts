import { BaseDocument } from '../core/model/document/base-document';

export class UserLoginDocument extends BaseDocument {
  get username() {
    return '' + this.body.username;
  }

  set username(value: string) {
    this.body.username = value;
  }

  get password() {
    return this.body.password;
  }

  set password(value: string) {
    this.body.password = value;
  }
}
