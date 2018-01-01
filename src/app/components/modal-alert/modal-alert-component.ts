import { Component, Input, Output, EventEmitter } from '@angular/core';

import { ModalDialog } from '../../core/modal/modal-dialog';

@Component({
  selector: 'app-modal-alert',
  templateUrl: './modal-alert-component.html',
  styleUrls: ['./modal-alert-component.scss']
})
export class ModalAlertComponent extends ModalDialog {
  @Input()
  title = 'Alert';

  @Input()
  message = '';

  @Output()
  accept = new EventEmitter<boolean>();
}
