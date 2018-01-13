import { BaseEntity } from '../../../core/model/base-entity';
import { InputOptionEntity } from '../../../entities/input-option-entity';

export class CategoryNewEntity extends BaseEntity {
  readonly id: string;
  title: string;
  parentCategoryId: string;
  categories: InputOptionEntity[];
}
