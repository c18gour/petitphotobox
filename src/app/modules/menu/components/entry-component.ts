import { Component, Input, Output, EventEmitter, ViewChild, QueryList } from '@angular/core';

import { MenuEntry } from './../entities/menu-entry';
import { MenuComponent } from './menu-component';

@Component({
  selector: 'app-entry',
  templateUrl: './entry-component.html',
  styleUrls: ['./entry-component.scss']
})
// TODO: rename by MenuEntryComponent
export class EntryComponent {
  private _isOpen = false;

  @Input()
  entry: MenuEntry;

  @Input()
  set open(value) {
    this.items.forEach((item) => {
      item.open = false;
    });

    this._isOpen = value;
  }

  get open(): boolean {
    return this._isOpen;
  }

  @Output()
  selectEntry = new EventEmitter<string>();

  @Output()
  toggleEntry = new EventEmitter<EntryComponent>();

  get selected() {
    return this.entry.selected === true;
  }

  @ViewChild('menu')
  menu: MenuComponent;

  get items() {
    return this.menu !== undefined
      ? this.menu.items
      : new QueryList<EntryComponent>();
  }

  onSelectEntry(categoryId: string) {
    this.selectEntry.emit(categoryId);
  }

  searchItemById(id: string): EntryComponent {
    const ret = this.items.map((item) => {
      if (item.entry.id === id) {
        return item;
      }

      return item.searchItemById(id);
    }).filter((item) => item !== null).pop();

    return ret !== undefined ? ret : null;
  }

  toggle() {
    this.open = !this.open;
    this.toggleEntry.emit(this);
  }
}
