// TODO: move this class to menu/
import {
  Component, Input, Output, EventEmitter, ViewChildren, QueryList
} from '@angular/core';

import { MenuEntry } from './../entities/menu-entry';
import { EntryComponent } from './entry-component';

@Component({
  selector: 'app-menu',
  templateUrl: './menu-component.html',
  styleUrls: ['./menu-component.scss'],
  inputs: ['open']
})
export class MenuComponent {
  private _isVisible = null;
  private _isOpen = false;

  @Input()
  entries: Array<MenuEntry> = [];

  @Input()
  set visible(value) {
    this._isVisible = value;
  }

  get visible() {
    return (this._isVisible !== null && this._isVisible) || this.open;
  }

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
  deleteEntry = new EventEmitter<string>();

  @ViewChildren('entries')
  items = new QueryList<EntryComponent>();

  onSelectEntry(categoryId) {
    this.selectEntry.emit(categoryId);
  }

  toggle() {
    this.open = !this.open;
  }
}
