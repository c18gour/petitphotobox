import { Component, Input, Output, EventEmitter } from '@angular/core';

import { ModalDialog } from '../../core/modal-dialog';

@Component({
  selector: 'app-modal-confirm',
  templateUrl: './modal-confirm-component.html',
  styleUrls: ['./modal-confirm-component.scss']
})
export class ModalConfirmComponent extends ModalDialog {
  @Input()
  title = 'Confirm';

  @Input()
  message = '';

  @Output()
  accept = new EventEmitter<boolean>();
}
