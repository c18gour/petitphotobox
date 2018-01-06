import {
  Component, Input, Output, EventEmitter, ViewChildren, QueryList
} from '@angular/core';
import {
  InputCheckboxOptionEntity
} from '../../entities/input-checkbox-option-entity';
import { InputCheckboxItemComponent } from './input-checkbox-item-component';

@Component({
  selector: 'app-input-checkbox-tree',
  templateUrl: './input-checkbox-tree-component.html',
  styleUrls: ['./input-checkbox-tree-component.scss']
})
export class InputCheckboxTreeComponent {
  private _isVisible = null;
  private _isOpen = true;

  @Input()
  set open(value) {
    this.entries.forEach((entry) => {
      entry.open = false;
    });

    this._isOpen = value;
  }

  get open(): boolean {
    return this._isOpen;
  }

  @Input()
  set visible(value) {
    this._isVisible = value;
  }

  get visible() {
    return (this._isVisible !== null && this._isVisible) || this.open;
  }

  @Input()
  value = new Array<String>();

  @Input()
  items: InputCheckboxOptionEntity[];

  @Output()
  select = new EventEmitter<string>();

  @ViewChildren('entries')
  entries = new QueryList<InputCheckboxItemComponent>();

  onChange(value: string) {
    console.log('changed!');
  }

  toggle() {
    this.open = !this.open;
  }
}
