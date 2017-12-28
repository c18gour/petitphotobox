export class MenuEntry {
  readonly id: string;
  title: string;
  open: boolean;
  selected: boolean;
  items: Array<MenuEntry>;
}
