import { BaseEntity } from '../core/entity/base-entity';
import { InputTreeOptionEntity } from './input-tree-option-entity';

export class PictureEntity extends BaseEntity {
  readonly id: string;
  path: string;
}
