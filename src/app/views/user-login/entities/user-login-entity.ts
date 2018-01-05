import { BaseEntity } from '../../../core/entity/base-entity';

export class UserLoginEntity extends BaseEntity {
  username: string;
  password: string;
}
