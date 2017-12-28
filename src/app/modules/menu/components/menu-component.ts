import {
  Component, Input, Output, EventEmitter, ViewChildren, QueryList
} from '@angular/core';

import { MenuEntry } from './../entities/menu-entry';
import { AbstractEntry } from '../core/abstract-entry';

@Component({
  selector: 'app-menu',
  templateUrl: './menu-component.html',
  styleUrls: ['./menu-component.scss'],
  inputs: ['open']
})
export class MenuComponent extends AbstractEntry {
  // private _isOpen = false;

  @Input()
  entries: Array<MenuEntry> = [];

  @Output()
  selectEntry = new EventEmitter<string>();

  @ViewChildren('entries')
  items: QueryList<AbstractEntry> = new QueryList<AbstractEntry>();

  // TODO: remove this method
  /*
  ngAfterViewInit() {
    // This workaround solves a known issue:
    //   https://github.com/angular/angular/issues/6005
    this._changeDetector.detectChanges();
  }*/

  onToggle(entry: AbstractEntry) {
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
