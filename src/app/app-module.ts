import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { HttpModule } from '@angular/http';

import { AppRoutingModule } from './app-routing-module';
import { AppComponent } from './app-component';

// modules
import { MenuModule } from './modules/menu/menu-module';

// views
import { HomeComponent } from './views/home/home-component';
import { UserLoginComponent } from './views/user-login/login-component';
import { UserRegisterComponent } from './views/user-register/user-register-component';
import { PageNotFoundComponent } from './views/page-not-found/page-not-found-component';

// controllers
import { UserLoginController } from './controllers/user-login-controller';
import { LogoutController } from './controllers/logout-controller';
import { HomeController } from './controllers/home-controller';

@NgModule({
  declarations: [
    AppComponent,
    HomeComponent,
    UserLoginComponent,
    UserRegisterComponent,
    PageNotFoundComponent
  ],
  imports: [
    BrowserModule,
    FormsModule,
    HttpModule,
    AppRoutingModule,
    MenuModule
  ],
  providers: [
    UserLoginController,
    LogoutController,
    HomeController
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
