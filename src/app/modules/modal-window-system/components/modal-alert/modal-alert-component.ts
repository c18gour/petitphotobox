import { Component, OnInit, ViewChild, Input, Output, EventEmitter, ElementRef } from '@angular/core';

import { ModalDialog } from '../../core/modal-dialog';

@Component({
  selector: 'app-modal-alert',
  templateUrl: './modal-alert-component.html',
  styleUrls: ['./modal-alert-component.scss']
})
export class ModalAlertComponent extends ModalDialog implements OnInit {
  @Input()
  title = 'Alert';

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
