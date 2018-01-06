import {
  Component, Input, Output, EventEmitter, ViewChild, QueryList
} from '@angular/core';
import {
  InputCheckboxOptionEntity
} from '../../entities/input-checkbox-option-entity';
import { InputCheckboxTreeComponent } from './input-checkbox-tree-component';

@Component({
  selector: 'app-input-checkbox-item',
  templateUrl: './input-checkbox-item-component.html',
  styleUrls: ['./input-checkbox-item-component.scss']
})
export class InputCheckboxItemComponent {
  @Input()
  set open(value) {
    this.entries.forEach((entry) => {
      entry.open = false;
    });

    this.item.open = value;
  }

  get open(): boolean {
    return this.item.open;
  }

  @Input()
  item: InputCheckboxOptionEntity;

  @ViewChild('menu')
  menu: InputCheckboxTreeComponent;

  @Output()
  toggleEntry = new EventEmitter<InputCheckboxItemComponent>();

  get entries() {
    return this.menu !== undefined
      ? this.menu.entries
      : new QueryList<InputCheckboxItemComponent>();
  }

  onChange(value: string) {
    console.log(value);
  }

  toggle() {
    this.open = !this.open;
    this.toggleEntry.emit(this);
  }
}
