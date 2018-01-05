import { BaseEntity } from '../../../core/entity/base-entity';
import { MenuEntry } from '../../../modules/menu/entities/menu-entry';

export class HomeEntity extends BaseEntity {
  readonly id: string;
  readonly main: boolean;
  title: string;
  categories: Array<MenuEntry>;
  pictures: Array<{ id: string, path: string }>;
}
