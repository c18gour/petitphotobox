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
  private _isOpen = false;

  @Input()
  entries: Array<MenuEntry> = [];

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

  @ViewChildren('entries')
  items = new QueryList<EntryComponent>();

  // TODO: remove this method
  /*
  ngAfterViewInit() {
    // This workaround solves a known issue:
    //   https://github.com/angular/angular/issues/6005
    this._changeDetector.detectChanges();
  }*/

  onToggle(entry: EntryComponent) {
    this.items.forEach((item) => {
      if (item !== entry) {
        item.open = false;
      }
    });
  }

  onSelect(categoryId) {
    this.selectEntry.emit(categoryId);
  }

  toggle() {
    this.open = !this.open;
  }
}
