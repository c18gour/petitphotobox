import { BaseEntity } from '../core/entity/base-entity';

export class HomeEntity extends BaseEntity {
  // TODO: replace object by something more specific
  categories: Array<object>;
  pictures: Array<object>;
}
