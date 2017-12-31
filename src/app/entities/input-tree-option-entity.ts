import { BaseEntity } from '../core/entity/base-entity';

export class InputTreeOptionEntity {
  value: string;
  label: string;
  items: InputTreeOptionEntity[];
}
