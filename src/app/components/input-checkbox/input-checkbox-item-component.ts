import {
  Component, Input, Output, EventEmitter, ViewChild, ElementRef, OnInit
} from '@angular/core';
import { InputOptionEntity } from '../../entities/input-option-entity';

@Component({
  selector: 'app-input-checkbox-item',
  templateUrl: './input-checkbox-item-component.html',
  styleUrls: ['./input-checkbox-item-component.scss']
})
export class InputCheckboxItemComponent implements OnInit {
  _isVisible: boolean = null;

  @Input()
  item: InputOptionEntity;

  @Input()
  value: string[] = [];

  @Output()
  selectEntry = new EventEmitter<string[]>();

  @ViewChild('input')
  input: ElementRef;

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

  get selected(): boolean {
    return this.value.indexOf(this.item.value) > -1;
  }

  ngOnInit() {
    if (this.open) {
      this._isVisible = true;
    }
  }

  searchItem(value: string, items?: any[]) {
    if (!items) {
      items = this.item.items;
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

  onChange(value: string) {
    const input = this.input.nativeElement;

    if (!input.checked) {
      const pos = this.value.indexOf(input.value);

      if (pos > -1) {
        this.value.splice(pos, 1);
      }
    } else {
      this.value.push(input.value);
    }

    this.selectEntry.emit(this.value);
  }

  toggle() {
    this._isVisible = !this._isVisible;
  }
}
