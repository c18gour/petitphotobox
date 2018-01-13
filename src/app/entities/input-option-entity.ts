import { BaseEntity } from '../core/model/base-entity';

export class InputOptionEntity {
  value: string;
  label: string;
  items: InputOptionEntity[];
}
