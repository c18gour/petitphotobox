import { Component, Input, Output, EventEmitter, ViewChild } from '@angular/core';

import { MenuComponent } from './menu-component';
import { MenuEntry } from './../entities/menu-entry';

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

  @Output()
  selectEntry = new EventEmitter<string>();

  get selected() {
    return this.entry.selected === true;
  }

  set open(value) {
    if (!value && this.menu) {
      this.menu.open = false;
    }

    this._isOpen = value;
  }

  get open() {
    return this._isOpen;
  }

  @ViewChild('menu')
  menu: MenuComponent;

  onSelectEntry(categoryId: string) {
    // this.selectEntry.emit(categoryId);
  }

  toggle() {
    this.open = !this.open;
  }
}
