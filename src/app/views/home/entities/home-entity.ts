import { BaseEntity } from '../../../core/model/base-entity';
import { MenuEntry } from '../../../modules/menu/entities/menu-entry';

export class HomeEntity extends BaseEntity {
  readonly id: string;
  readonly main: boolean;
  username: string;
  title: string;
  categories: Array<MenuEntry>;
  page: number;
  numPages: number;
  pictures: HomePictureEntity[];
}

export class HomePictureEntity {
  id: string;
  categories: number;
  snapshots: number;
  path: string;
}
