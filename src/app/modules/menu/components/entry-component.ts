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

    this.entry.selected = false;
  }

  get open() {
    return this.entry.selected || (this.menu && this.menu.open);
  }

  @ViewChild('menu')
  menu: MenuComponent;

  onSelectEntry(categoryId: string) {
    this.selectEntry.emit(categoryId);
  }
}
