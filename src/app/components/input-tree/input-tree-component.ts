import { Component, Input, Output, EventEmitter } from '@angular/core';
import { InputTreeOptionEntity } from '../../entities/input-tree-option-entity';

@Component({
  selector: 'app-input-tree',
  templateUrl: './input-tree-component.html',
  styleUrls: ['./input-tree-component.scss']
})
export class InputTreeComponent {
  @Input()
  name = '';

  @Input()
  value = '';

  @Input()
  items: InputTreeOptionEntity[] = [];

  @Output()
  select = new EventEmitter<string>();

  get selectedItem() {
    return this.searchSelectedItem(this.inputValue);
  }

  get inputValue() {
    const item = this.searchSelectedItem(this.value);

    return item ? item.value : '';
  }

  set inputValue(value: string) {
    this.value = value;
  }

  searchSelectedItem(value: string, items?: any[]) {
    if (!items) {
      items = this.items;
    }

    for (const item of items) {
      if (item.value === value || this.searchSelectedItem(value, item.items)) {
        return item;
      }
    }

    return null;
  }

  onSelect(value: string) {
    this.value = value;
    this.select.emit(value);
  }

  onChange(value: string) {
    const item = this.selectedItem;

    if (item) {
      this.select.emit(item.value);
    }
  }
}
