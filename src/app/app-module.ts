import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { HttpModule } from '@angular/http';

/**
 * Application module.
 */
import { AppRoutingModule } from './/app-routing-module';
import { AppComponent } from './app-component';

/**
 * Views.
 */
import { HomeComponent } from './components/home/home-component';
import { MenuComponent } from './components/home/menu/menu-component';
import { EntryComponent } from './components/home/menu/entry/entry-component';

import { UserLoginComponent } from './components/user-login/login-component';
import { UserRegisterComponent } from './components/user-register/user-register-component';
import { PageNotFoundComponent } from './components/page-not-found/page-not-found-component';

/**
 * Controllers.
 */
import { UserLoginController } from './controllers/user-login-controller';
import { LogoutController } from './controllers/logout-controller';
import { HomeController } from './controllers/home-controller';

@NgModule({
  declarations: [
    AppComponent,

    // views
    HomeComponent,
    MenuComponent,
    EntryComponent,

    UserLoginComponent,
    UserRegisterComponent,
    PageNotFoundComponent
  ],
  imports: [
    BrowserModule,
    FormsModule,
    HttpModule,
    AppRoutingModule
  ],
  providers: [
    UserLoginController,
    LogoutController,
    HomeController
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
