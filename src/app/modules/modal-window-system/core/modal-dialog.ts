import { EventEmitter } from '@angular/core';

export abstract class ModalDialog {
  title: string;
  message: string;
  readonly accept: EventEmitter<boolean>;
}
