import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { HttpModule } from '@angular/http';
import { AutofocusModule } from 'angular-autofocus-fix';

import { AppRoutingModule } from './app-routing-module';
import { AppComponent } from './app-component';

// modules
import { MenuModule } from './modules/menu/menu-module';
import {
  ModalWindowSytemModule
} from './modules/modal-window-system/modal-window-system-module';

// components
import {
  InputTreeComponent
} from './components/input-tree/input-tree-component';
import { PictureComponent } from './components/picture/picture-component';

// views
import { HomeView } from './views/home/home-view';
import { UserLoginView } from './views/user-login/user-login-view';
import { UserRegisterView } from './views/user-register/user-register-view';
import { CategoryNewView } from './views/category-new/category-new';
import { CategoryEditView } from './views/category-edit/category-edit';
import { PictureNewView } from './views/picture-new/picture-new';
import { PageNotFoundView } from './views/page-not-found/page-not-found-view';

// controllers
import {
  UserLoginController
} from './views/user-login/controllers/user-login-controller';
import { CategoryNewController } from './controllers/category-new-controller';
import { CategoryEditController } from './controllers/category-edit-controller';

// home controllers
import { HomeController } from './views/home/controllers/home-controller';
import { LogoutController } from './views/home/controllers/logout-controller';
import {
  CategoryDeleteController
} from './views/home/controllers/category-delete-controller';
import {
  PictureDeleteController
} from './views/home/controllers/picture-delete-controller';
import {
  PictureUpController
} from './views/home/controllers/picture-up-controller';
import {
  PictureDownController
} from './views/home/controllers/picture-down-controller';

@NgModule({
  declarations: [
    AppComponent,

    // components
    InputTreeComponent,
    PictureComponent,

    // views
    HomeView,
    UserLoginView,
    UserRegisterView,
    CategoryNewView,
    CategoryEditView,
    PictureNewView,
    PageNotFoundView
  ],
  imports: [
    BrowserModule,
    AutofocusModule,
    FormsModule,
    HttpModule,
    AppRoutingModule,
    ModalWindowSytemModule,
    MenuModule
  ],
  providers: [
    UserLoginController,
    LogoutController,
    HomeController,
    CategoryNewController,
    CategoryEditController,
    CategoryDeleteController,
    PictureDeleteController,
    PictureUpController,
    PictureDownController
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
