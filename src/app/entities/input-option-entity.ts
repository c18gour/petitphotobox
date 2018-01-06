import { BaseEntity } from '../core/entity/base-entity';

// TODO: rename by InputSelectOptionEntity
export class InputOptionEntity {
  value: string;
  label: string;
  items: InputOptionEntity[];
}
