import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { HttpModule } from '@angular/http';
import { AutofocusModule } from 'angular-autofocus-fix';

import { AppRoutingModule } from './app-routing-module';
import { AppComponent } from './app-component';

// modules
import { MenuModule } from './modules/menu/menu-module';

// components
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
import {
  InputTreeComponent
} from './components/input-tree/input-tree-component';

// views
import { HomeView } from './views/home/home-view';
import { UserLoginView } from './views/user-login/user-login-view';
import { UserRegisterView } from './views/user-register/user-register-view';
import { CategoryNewView } from './views/category-new/category-new';
import { PageNotFoundView } from './views/page-not-found/page-not-found-view';

// controllers
import { UserLoginController } from './controllers/user-login-controller';
import { LogoutController } from './controllers/logout-controller';
import { HomeController } from './controllers/home-controller';
import { CategoryNewController } from './controllers/category-new-controller';

@NgModule({
  declarations: [
    AppComponent,

    // components
    ModalLoadingComponent,
    ModalAlertComponent,
    ModalConfirmComponent,
    ModalErrorComponent,
    InputTreeComponent,

    // views
    HomeView,
    UserLoginView,
    UserRegisterView,
    CategoryNewView,
    PageNotFoundView
  ],
  imports: [
    BrowserModule,
    AutofocusModule,
    FormsModule,
    HttpModule,
    AppRoutingModule,
    MenuModule
  ],
  providers: [
    UserLoginController,
    LogoutController,
    HomeController,
    CategoryNewController
  ],
  entryComponents: [
    ModalLoadingComponent,
    ModalAlertComponent,
    ModalConfirmComponent,
    ModalErrorComponent
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
