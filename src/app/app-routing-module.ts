import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

import { HomeView } from './views/home/home-view';
import { UserLoginComponent } from './views/user-login/login-component';
import { UserRegisterComponent } from './views/user-register/user-register-component';
import { PageNotFoundView } from './views/page-not-found/page-not-found-view';

const routes: Routes = [
  { path: 'home', component: HomeView },
  { path: 'home/:id', component: HomeView },
  { path: 'login', component: UserLoginComponent },
  { path: 'user-register', component: UserRegisterComponent },
  { path: '', redirectTo: 'home', pathMatch: 'full' },
  { path: '**', component: PageNotFoundView }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
