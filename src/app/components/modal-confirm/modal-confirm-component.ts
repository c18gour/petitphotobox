import { Component, Input, Output, EventEmitter } from '@angular/core';

@Component({
  selector: 'app-modal-confirm',
  templateUrl: './modal-confirm-component.html',
  styleUrls: ['./modal-confirm-component.scss']
})
export class ModalConfirmComponent {
  @Input()
  hidden = false;

  @Input()
  title = 'Confirm';

  @Input()
  data: any;

  @Input()
  message = '';

  @Output()
  accept = new EventEmitter<any>();

  @Output()
  cancel = new EventEmitter();
}
