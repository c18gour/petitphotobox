import { BaseEntity } from '../../../core/model/base-entity';

export class UserSettingsEntity extends BaseEntity {
  name: string;
  language: string;
}
