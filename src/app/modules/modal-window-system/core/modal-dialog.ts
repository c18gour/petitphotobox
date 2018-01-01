import { EventEmitter } from '@angular/core';

// TODO: the entire modal system should be a module
export abstract class ModalDialog {
  title: string;
  message: string;
  readonly accept: EventEmitter<boolean>;
}
