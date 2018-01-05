import { BaseEntity } from '../../../core/entity/base-entity';
import {
  InputTreeOptionEntity
} from '../../../entities/input-tree-option-entity';

export class CategoryEditEntity extends BaseEntity {
  readonly id: string;
  title: string;
  parentCategoryId: string;
  categories: InputTreeOptionEntity[];
}
