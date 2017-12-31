import { Component, Input } from '@angular/core';

@Component({
  selector: 'app-modal-loading',
  templateUrl: './modal-loading-component.html',
  styleUrls: ['./modal-loading-component.scss']
})
export class ModalLoadingComponent {
  @Input()
  hidden = false;

  open() {
    this.hidden = false;
  }

  close() {
    this.hidden = true;
  }
}
