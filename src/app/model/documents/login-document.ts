import { BaseDocument } from '../../core/model/document/base-document';

export class LoginDocument extends BaseDocument {

  get username(): string {
    return this.document.username;
  }

  get password(): string {
    return this.document.password;
  }
}
