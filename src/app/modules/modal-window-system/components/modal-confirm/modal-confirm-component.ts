import {
  Component, OnInit, ViewChild, Input, Output, EventEmitter, ElementRef
} from '@angular/core';

import { ModalDialog } from '../../core/modal-dialog';

@Component({
  selector: 'app-modal-confirm',
  templateUrl: './modal-confirm-component.html',
  styleUrls: ['./modal-confirm-component.scss']
})
export class ModalConfirmComponent extends ModalDialog implements OnInit {
  @Input()
  title = 'Confirm';

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
