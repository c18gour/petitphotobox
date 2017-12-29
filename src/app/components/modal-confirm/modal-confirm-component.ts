import { Component, Input, Output, EventEmitter } from '@angular/core';

@Component({
  selector: 'app-modal-confirm',
  templateUrl: './modal-confirm-component.html',
  styleUrls: ['./modal-confirm-component.scss']
})
export class ModalConfirmComponent {
  @Input()
  hidden = true;

  @Input()
  title = 'Alert';

  @Input()
  data: any;

  @Input()
  message = '';

  @Output()
  accept = new EventEmitter<any>();

  open(data?: any) {
    this.hidden = false;
    this.data = data;
  }

  close() {
    this.hidden = true;
    this.data = undefined;
  }
}
