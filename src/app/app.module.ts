import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { HttpModule } from '@angular/http';

import { AppRoutingModule } from './/app-routing.module';
import { AppComponent } from './app.component';
import { HomeComponent } from './components/home/home.component';
import { LoginComponent } from './components/login/login.component';
import { SinginComponent } from './components/singin/singin.component';
import { PageNotFoundComponent } from './components/page-not-found/page-not-found.component';

import { LoginController } from './controllers/login-controller';

@NgModule({
  declarations: [
    AppComponent,
    HomeComponent,
    LoginComponent,
    SinginComponent,
    PageNotFoundComponent
  ],
  imports: [
    BrowserModule,
    FormsModule,
    HttpModule,
    AppRoutingModule
  ],
  providers: [
    LoginController
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
