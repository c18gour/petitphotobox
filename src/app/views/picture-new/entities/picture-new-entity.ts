import { BaseEntity } from '../../../core/model/base-entity';
import { InputOptionEntity } from '../../../entities/input-option-entity';

export class PictureNewEntity extends BaseEntity {
  readonly id: string;
  title: string;
  tags: string;
  categoryIds: string[];
  categories: InputOptionEntity[];
}
