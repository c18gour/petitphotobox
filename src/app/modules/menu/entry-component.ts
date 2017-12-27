import { Component, Input, Output, EventEmitter, ViewChild, AfterViewInit } from '@angular/core';

import { MenuComponent } from './menu-component';
import { MenuEntry } from './entities/menu-entry';

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

  @Output()
  set isSelected(value) {
    this.entry.selected = false;
  }

  get isSelected() {
    return this.entry.selected === true;
  }

  get isOpen() {
    return this.isSelected || this.menu !== undefined && this.menu.isOpen;
  }

  @ViewChild('menu')
  menu: MenuComponent;

  onSelectEntry(categoryId: string) {
    this.selectEntry.emit(categoryId);
  }
}
