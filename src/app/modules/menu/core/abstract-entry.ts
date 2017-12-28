import { QueryList } from '@angular/core';

export abstract class AbstractEntry {
  private _isOpen = false;

  get open(): boolean {
    return this._isOpen;
  }

  set open(value) {
    this.items.forEach((item) => {
      item.open = false;
    });

    this._isOpen = value;
  }

  abstract get items(): QueryList<AbstractEntry>;
}
