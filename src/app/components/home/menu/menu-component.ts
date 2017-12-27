import { Component, Input, Output, EventEmitter, ViewChildren } from '@angular/core';

@Component({
  selector: 'app-menu',
  templateUrl: './menu-component.html',
  styleUrls: ['./menu-component.scss']
})
export class MenuComponent {
  private _isOpen = false;

  @Input()
  entries = [];

  @Output()
  selectEntry = new EventEmitter<string>();

  @ViewChildren('items')
  items;

  onSelect(categoryId) {
    this.selectEntry.emit(categoryId);
  }
}
