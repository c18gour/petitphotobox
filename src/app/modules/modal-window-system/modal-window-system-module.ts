import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HttpClientModule, HttpClient } from '@angular/common/http';
import { TranslateModule, TranslateLoader } from '@ngx-translate/core';
import { TranslateHttpLoader } from '@ngx-translate/http-loader';

import { ModalWindowSystem } from './modal-window-system';
import { ModalLoadingComponent } from './components/modal-loading/modal-loading-component';
import { ModalAlertComponent } from './components/modal-alert/modal-alert-component';
import { ModalConfirmComponent } from './components/modal-confirm/modal-confirm-component';
import { ModalErrorComponent } from './components/modal-error/modal-error-component';

// AoT requires an exported function for factories
export function HttpLoaderFactory(http: HttpClient) {
  return new TranslateHttpLoader(http);
}

@NgModule({
  imports: [
    CommonModule,
    HttpClientModule,
    TranslateModule.forRoot({
      loader: {
        provide: TranslateLoader,
        useFactory: HttpLoaderFactory,
        deps: [HttpClient]
      }
    })
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
