import {
  Component, Input, Output, EventEmitter, ViewChildren, QueryList, OnInit
} from '@angular/core';
import {
  InputCheckboxOptionEntity
} from '../../entities/input-checkbox-option-entity';
import { InputCheckboxItemComponent } from './input-checkbox-item-component';

@Component({
  selector: 'app-input-menu-tree',
  templateUrl: './input-checkbox-menu-component.html',
  styleUrls: ['./input-checkbox-menu-component.scss']
})
export class InputCheckboxMenuComponent implements OnInit {
  _isVisible: boolean = null;

  @Input()
  items: InputCheckboxOptionEntity[];

  @Input()
  value: string[] = [];

  @Output()
  selectEntry = new EventEmitter<string[]>();

  @Input()
  set visible(value: boolean) {
    this._isVisible = value;
  }

  get visible(): boolean {
    return this._isVisible !== null ? this._isVisible : this.open;
  }

  get open(): boolean {
    for (const v of this.value) {
      const item = this.searchItem(v.toString());

      if (item !== null) {
        return true;
      }
    }

    return false;
  }

  searchItem(value: string, items?: any[]) {
    if (!items) {
      items = this.items;
    }

    for (const item of items) {
      if (item.value === value || this.searchItem(value, item.items)) {
        return item;
      }
    }

    return null;
  }

  ngOnInit() {
    if (this.open) {
      this._isVisible = true;
    }
  }

  onSelect(value: string[]) {
    this.value = value;
    this.selectEntry.emit(this.value);
  }
}
