import { Component, Input, Output, EventEmitter, ViewChild, QueryList } from '@angular/core';

import { MenuComponent } from './menu-component';
import { MenuEntry } from './../entities/menu-entry';
import { AbstractEntry } from '../core/abstract-entry';

@Component({
  selector: 'app-entry',
  templateUrl: './entry-component.html',
  styleUrls: ['./entry-component.scss'],
  inputs: ['open']
})
// TODO: rename by MenuEntryComponent
export class EntryComponent extends AbstractEntry {
  @Input()
  entry: MenuEntry;

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
      : new QueryList<AbstractEntry>();
  }

  onSelectEntry(categoryId: string) {
    this.selectEntry.emit(categoryId);
  }

  toggle() {
    this.open = !this.open;
    this.toggleEntry.emit(this);
  }
}
