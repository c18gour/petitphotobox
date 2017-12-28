import {
  Component, Input, Output, AfterViewInit, EventEmitter, ViewChildren,
  ChangeDetectorRef, QueryList
} from '@angular/core';

import { MenuEntry } from './../entities/menu-entry';
import { EntryComponent } from './entry-component';

@Component({
  selector: 'app-menu',
  templateUrl: './menu-component.html',
  styleUrls: ['./menu-component.scss']
})
export class MenuComponent implements AfterViewInit {
  private _isOpen = false;
  private _isHidden = null;

  @Input()
  entries: Array<MenuEntry> = [];

  constructor(private _changeDetector: ChangeDetectorRef) { }

  @Input()
  set open(value) {
    this._isOpen = value;
  }

  get open() {
    return this._isOpen
      || this.items.some((item: EntryComponent) => item.open);
  }

  @Input()
  set isHidden(value) {
    this._isHidden = value;
  }

  get isHidden() {
    /*
    let ret = true;

    if (this._isHidden !== null) {
      ret = this._isHidden;
    } else {
      ret = !this.open;
    }*/

    return (this._isHidden !== null && this._isHidden) || !this.open;
  }

  @Output()
  selectEntry = new EventEmitter<string>();

  @ViewChildren('entries')
  items: QueryList<EntryComponent> = new QueryList<EntryComponent>();

  ngAfterViewInit() {
    // This workaround solves a known issue:
    //   https://github.com/angular/angular/issues/6005
    this._changeDetector.detectChanges();
  }

  onSelect(categoryId) {
    this.open = true;
    this.selectEntry.emit(categoryId);
  }

  toggle() {
    this.isHidden = !this.isHidden;
  }
}
