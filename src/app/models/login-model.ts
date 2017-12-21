import { BaseModel } from '../core/model/base-model';

export class LoginModel extends BaseModel {

  get username(): string {
    return this.model.username;
  }

  get password(): string {
    return this.model.password;
  }
}
