import { Component, Input, OnInit, ViewChild, ElementRef } from '@angular/core';

@Component({
  selector: 'app-modal-loading',
  templateUrl: './modal-loading-component.html',
  styleUrls: ['./modal-loading-component.scss']
})
export class ModalLoadingComponent implements OnInit {
  @Input()
  hidden = false;

  @ViewChild('modalDialog')
  modalDialog: ElementRef;

  ngOnInit() {
    this.modalDialog.nativeElement.focus();
  }
}
