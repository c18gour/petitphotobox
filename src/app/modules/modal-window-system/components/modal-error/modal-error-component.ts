import { Component, Input, Output, EventEmitter } from '@angular/core';

import { ModalDialog } from '../../core/modal-dialog';

@Component({
  selector: 'app-modal-error',
  templateUrl: './modal-error-component.html',
  styleUrls: ['./modal-error-component.scss']
})
export class ModalErrorComponent extends ModalDialog {
  @Input()
  title = 'Error';

  @Input()
  message = '';

  @Output()
  accept = new EventEmitter<boolean>();
}
