import {
  Component, Input, Output, EventEmitter, ViewChild, QueryList, ElementRef,
  OnInit
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
export class InputCheckboxItemComponent implements OnInit {
  _isVisible: boolean = null;

  @Input()
  item: InputCheckboxOptionEntity;

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

  ngOnInit() {
    if (this.open) {
      this._isVisible = true;
    }
  }

  onSelect(value: string[]) {
    this.value = value;
    this.selectEntry.emit(this.value);
  }

  onChange(event: Event) {
    const input = <HTMLInputElement>event.target;

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
