import { Component, Input, Output, EventEmitter } from '@angular/core';

@Component({
  selector: 'app-modal-alert',
  templateUrl: './modal-alert-component.html',
  styleUrls: ['./modal-alert-component.scss']
})
export class ModalAlertComponent {
  @Input()
  hidden = false;

  @Input()
  title = 'Alert';

  @Input()
  data: any;

  @Input()
  message = '';

  @Output()
  accept = new EventEmitter<any>();
}
