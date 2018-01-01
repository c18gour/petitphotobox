import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { ModalWindowSystem } from './modal-window-system';
import {
  ModalLoadingComponent
} from './components/modal-loading/modal-loading-component';
import {
  ModalAlertComponent
} from './components/modal-alert/modal-alert-component';
import {
  ModalConfirmComponent
} from './components/modal-confirm/modal-confirm-component';
import {
  ModalErrorComponent
} from './components/modal-error/modal-error-component';

@NgModule({
  imports: [
    CommonModule
  ],
  declarations: [
    ModalLoadingComponent,
    ModalAlertComponent,
    ModalConfirmComponent,
    ModalErrorComponent
  ],
  entryComponents: [
    ModalLoadingComponent,
    ModalAlertComponent,
    ModalConfirmComponent,
    ModalErrorComponent
  ]
})
export class ModalWindowSytemModule { }
