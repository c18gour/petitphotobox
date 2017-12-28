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

  @Input()
  entries: Array<MenuEntry> = [];

  constructor(private _changeDetector: ChangeDetectorRef) { }

  @Input()
  set isOpen(value) {
    this._isOpen = value;
  }

  get isOpen() {
    return this._isOpen
      || this.items.some((item: EntryComponent) => item.isOpen);
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
    this.isOpen = true;
    this.selectEntry.emit(categoryId);
  }

  toggle() {
    this.isOpen = !this.isOpen;
  }
}
