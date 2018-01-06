import { Component, Input } from '@angular/core';

import {
  InputCheckboxOptionEntity
} from '../../entities/input-checkbox-option-entity';

@Component({
  selector: 'app-input-checkbox',
  template: `
  <app-input-menu-tree
    [items]="items" [value]="value" [visible]="true"></app-input-menu-tree>
  `
})
export class InputCheckboxTreeComponent {
  @Input()
  value: string[] = [];

  @Input()
  items: InputCheckboxOptionEntity[];
}
