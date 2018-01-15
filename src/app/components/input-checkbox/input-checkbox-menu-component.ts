import { Component, Input, Output, EventEmitter, OnInit } from '@angular/core';
import { InputOptionEntity } from '../../entities/input-option-entity';
import { InputCheckboxItemComponent } from './input-checkbox-item-component';

@Component({
  selector: 'app-input-checkbox-menu',
  templateUrl: './input-checkbox-menu-component.html',
  styleUrls: ['./input-checkbox-menu-component.scss']
})
export class InputCheckboxMenuComponent implements OnInit {
  private _isVisible: boolean = null;

  @Output()
  selectEntry = new EventEmitter<string[]>();

  @Input()
  required = false;

  @Input()
  items: InputOptionEntity[];

  @Input()
  value: string[] = [];

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

  ngOnInit() {
    if (this.open) {
      this._isVisible = true;
    }
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

  onSelect(value: string[]) {
    this.value = value;
    this.selectEntry.emit(this.value);
  }
}
