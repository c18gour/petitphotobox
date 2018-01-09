import { BaseEntity } from '../../../core/entity/base-entity';
import {
  InputOptionEntity
} from '../../../entities/input-option-entity';

export class PictureEditEntity extends BaseEntity {
  readonly id: string;
  title: string;
  tags: string;
  categoryIds: string[];
  categories: InputOptionEntity[];
}
