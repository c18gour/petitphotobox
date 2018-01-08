import { BaseEntity } from '../../../core/entity/base-entity';
import {
  InputOptionEntity
} from '../../../entities/input-option-entity';

export class PictureNewEntity extends BaseEntity {
  readonly id: string;
  title: string;
  categoryIds: string[];
  categories: InputOptionEntity[];
}
