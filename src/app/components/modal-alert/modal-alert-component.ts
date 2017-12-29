import { Component, Input, Output, EventEmitter } from '@angular/core';

@Component({
  selector: 'app-modal-alert',
  templateUrl: './modal-alert-component.html',
  styleUrls: ['./modal-alert-component.scss']
})
export class ModalAlertComponent {
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
