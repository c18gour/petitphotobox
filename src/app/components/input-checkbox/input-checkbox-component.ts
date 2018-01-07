import { Component, Input } from '@angular/core';

import {
  InputOptionEntity
} from '../../entities/input-option-entity';

@Component({
  selector: 'app-input-checkbox',
  template: `
    <app-input-checkbox-menu
      [items]="items" [value]="value"
      [visible]="true"></app-input-checkbox-menu>`
})
export class InputCheckboxComponent {
  @Input()
  value: string[] = [];

  @Input()
  items: InputOptionEntity[];
}
