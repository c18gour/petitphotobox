export class MenuEntry {
  readonly id: string;
  title: string;
  selected: boolean;
  items: Array<MenuEntry>;
}
