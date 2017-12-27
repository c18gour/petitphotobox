import { BaseEntity } from '../core/entity/base-entity';
import { MenuEntry } from '../modules/menu/entities/menu-entry';

export class HomeEntity extends BaseEntity {
  categories: Array<MenuEntry>;
  pictures: Array<{ id: string, path: string }>;
}
