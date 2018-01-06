import { BaseEntity } from '../core/entity/base-entity';

export class InputCheckboxOptionEntity {
  value: string;
  label: string;
  open: boolean;
  items: InputCheckboxOptionEntity[];
}
