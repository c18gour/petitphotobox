import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { HttpModule } from '@angular/http';

// modules
import { MenuModule } from './modules/menu/menu-module';

// components
import { AppRoutingModule } from './app-routing-module';
import { AppComponent } from './app-component';
import { HomeComponent } from './components/home/home-component';
import { UserLoginComponent } from './components/user-login/login-component';
import { UserRegisterComponent } from './components/user-register/user-register-component';
import { PageNotFoundComponent } from './components/page-not-found/page-not-found-component';

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
