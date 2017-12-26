import { BaseEntity } from '../core/entity/base-entity';

export class HomeEntity extends BaseEntity {
  categories: Array<CategoryType>;
  pictures: Array<{ id: string, path: string }>;
}

interface CategoryType {
  id: string;
  title: string;
  selected: boolean;
  items: Array<CategoryType>;
}
