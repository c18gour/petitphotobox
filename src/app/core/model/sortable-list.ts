export class SortableList<Type> {
  constructor(private _items: Array<Type> = []) { }

  addItem(item: Type) {
    const pos = this._items.indexOf(item);

    if (pos >= 0) {
      throw new Error('Item already added');
    }

    this._items.push(item);
  }

  removeItem(item: Type) {
    const pos = this._items.indexOf(item);

    if (pos < 0) {
      throw new Error('Item not found');
    }

    this._items.splice(pos, 1);
  }

  moveItemUp(item: Type) {
    const pos = this._items.indexOf(item);

    if (pos < 0) {
      throw new Error('Item not found');
    }

    this._swapItems(pos, pos - 1);
  }

  moveItemDown(item: Type) {
    const pos = this._items.indexOf(item);

    if (pos < 0) {
      throw new Error('Item not found');
    }

    this._swapItems(pos, pos + 1);
  }

  get items() {
    return this._items;
  }

  private _swapItems(fromPos: number, toPos: number) {
    const item = this._items[fromPos];

    this._items.splice(fromPos, 1);
    this._items.splice(toPos, 0, item);
  }
}
