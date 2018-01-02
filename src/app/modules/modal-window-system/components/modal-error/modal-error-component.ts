import {
  Component, OnInit, ViewChild, Input, Output, EventEmitter, ElementRef
} from '@angular/core';

import { ModalDialog } from '../../core/modal-dialog';

@Component({
  selector: 'app-modal-error',
  templateUrl: './modal-error-component.html',
  styleUrls: ['./modal-error-component.scss']
})
export class ModalErrorComponent extends ModalDialog implements OnInit {
  @Input()
  title = 'Error';

  @Input()
  message = '';

  @Output()
  accept = new EventEmitter<boolean>();

  @ViewChild('acceptButton')
  acceptButton: ElementRef;

  ngOnInit() {
    this.acceptButton.nativeElement.focus();
  }
}
