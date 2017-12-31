import { Component, Input, Output, EventEmitter } from '@angular/core';

@Component({
  selector: 'app-modal-error',
  templateUrl: './modal-error-component.html',
  styleUrls: ['./modal-error-component.scss']
})
export class ModalErrorComponent {
  @Input()
  hidden = false;

  @Input()
  title = 'Error';

  @Input()
  data: any;

  @Input()
  message = '';

  @Output()
  accept = new EventEmitter<any>();
}
