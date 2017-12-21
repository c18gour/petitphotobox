import { BaseDocument } from '../core/document/base-document';

export class LoginModel extends BaseDocument {

  get username(): string {
    return this.document.username;
  }

  get password(): string {
    return this.document.password;
  }
}
