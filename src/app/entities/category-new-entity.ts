import { BaseEntity } from '../core/entity/base-entity';
import { InputTreeOptionEntity } from './input-tree-option-entity';

export class CategoryNewEntity extends BaseEntity {
  readonly id: string;
  title: string;
  parentCategoryId: string;
  categories: InputTreeOptionEntity[];
}
