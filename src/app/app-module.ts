import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { HttpModule } from '@angular/http';

import { AppRoutingModule } from './/app-routing-module';

// components
import { AppComponent } from './app-component';
import { MenuComponent } from './components/menu/menu-component';
import { EntryComponent } from './components/menu/entry/entry-component';
import { HomeComponent } from './components/home/home-component';
import { UserLoginComponent } from './components/user-login/login-component';
import { UserRegisterComponent } from './components/user-register/user-register-component';
import { PageNotFoundComponent } from './components/page-not-found/page-not-found-component';

// services and controllers
import { UserLoginController } from './controllers/user-login-controller';
import { LogoutController } from './controllers/logout-controller';
import { HomeController } from './controllers/home-controller';

@NgModule({
  declarations: [
    AppComponent,
    MenuComponent,
    EntryComponent,
    HomeComponent,
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
