import { BaseEntity } from '../../../core/model/base-entity';
import { InputOptionEntity } from '../../../entities/input-option-entity';

export class SearchEntity extends BaseEntity {
  categoryIds: string[];
  categories: InputOptionEntity[];
  page: number;
  numPages: number;
  pictures: SearchPictureEntity[];
}

export class SearchPictureEntity {
  id: string;
  categories: number;
  snapshots: number;
  path: string;
}
