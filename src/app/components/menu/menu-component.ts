import { Component, Input, Output, EventEmitter } from '@angular/core';

@Component({
  selector: 'app-menu',
  templateUrl: './menu-component.html',
  styleUrls: ['./menu-component.scss']
})
export class MenuComponent {
  @Input()
  entries = [];

  @Output()
  selectEntry = new EventEmitter<string>();

  onSelectEntry(categoryId: string) {
    this.selectEntry.emit(categoryId);
  }
}
