import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { HttpModule } from '@angular/http';
import { AutofocusModule } from 'angular-autofocus-fix';
import { FileUploadModule } from 'ng2-file-upload';
import { RoundProgressModule } from 'angular-svg-round-progressbar';

import { AppRoutingModule } from './app-routing-module';
import { AppComponent } from './app-component';

// modules
import { MenuModule } from './modules/menu/menu-module';
import {
  ModalWindowSytemModule
} from './modules/modal-window-system/modal-window-system-module';

// components
import {
  InputSelectComponent
} from './components/input-select/input-select-component';
import {
  PictureUploaderComponent
} from './components/picture-uploader/picture-uploader-component';
import {
  PictureComponent
} from './components/picture/picture-component';

// input-checkbox component
import {
  InputCheckboxComponent
} from './components/input-checkbox/input-checkbox-component';
import {
  InputCheckboxMenuComponent
} from './components/input-checkbox/input-checkbox-menu-component';
import {
  InputCheckboxItemComponent
} from './components/input-checkbox/input-checkbox-item-component';

// search components
import {
  SearchPictureComponent
} from './views/search/components/search-picture/search-picture-component';

// picture-new components
import { PictureNewSnapshotComponent } from './views/picture-new/components/picture-new-snapshot/picture-new-snapshot-component';

// views
import { HomeView } from './views/home/home-view';
import { UserLoginView } from './views/user-login/user-login-view';
import { UserRegisterView } from './views/user-register/user-register-view';
import { CategoryNewView } from './views/category-new/category-new-view';
import { CategoryEditView } from './views/category-edit/category-edit-view';
import { PictureNewView } from './views/picture-new/picture-new-view';
import { PictureEditView } from './views/picture-edit/picture-edit-view';
import { SearchView } from './views/search/search-view';
import { PageNotFoundView } from './views/page-not-found/page-not-found-view';

// controllers
import { LogoutController } from './controllers/logout-controller';
import {
  UserLoginController
} from './views/user-login/controllers/user-login-controller';
import {
  CategoryNewController
} from './views/category-new/controllers/category-new-controller';
import {
  CategoryEditController
} from './views/category-edit/controllers/category-edit-controller';
import {
  PictureNewController
} from './views/picture-new/controllers/picture-new-controller';
import {
  PictureEditController
} from './views/picture-edit/controllers/picture-edit-controller';
import { SearchController } from './views/search/controllers/search-controller';

// home controllers
import { HomeController } from './views/home/controllers/home-controller';
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
    InputSelectComponent,
    PictureUploaderComponent,
    PictureComponent,
    SearchPictureComponent,

    // input-checkbox component
    InputCheckboxComponent,
    InputCheckboxMenuComponent,
    InputCheckboxItemComponent,

    // picture-new components
    PictureNewSnapshotComponent,

    // views
    HomeView,
    UserLoginView,
    UserRegisterView,
    CategoryNewView,
    CategoryEditView,
    PictureNewView,
    PictureEditView,
    SearchView,
    PageNotFoundView
  ],
  imports: [
    BrowserModule,
    AutofocusModule,
    FormsModule,
    HttpModule,
    AppRoutingModule,
    ModalWindowSytemModule,
    MenuModule,
    FileUploadModule,
    RoundProgressModule
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
    PictureDownController,
    PictureNewController,
    PictureEditController,
    SearchController
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
