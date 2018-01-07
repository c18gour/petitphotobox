import { BaseEntity } from '../core/entity/base-entity';

export class InputOptionEntity {
  value: string;
  label: string;
  items: InputOptionEntity[];
}
