import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

import { HomeView } from './views/home/home-view';
import { UserLoginView } from './views/user-login/user-login-view';
import { UserRegisterView } from './views/user-register/user-register-view';
import { CategoryNewView } from './views/category-new/category-new';
import { CategoryEditView } from './views/category-edit/category-edit';
import { PictureNewView } from './views/picture-new/picture-new';
import { PageNotFoundView } from './views/page-not-found/page-not-found-view';

const routes: Routes = [
  { path: 'home', component: HomeView },
  { path: 'home/:id', component: HomeView },
  { path: 'login', component: UserLoginView },
  { path: 'user-register', component: UserRegisterView },
  { path: 'category/new', component: CategoryNewView },
  { path: 'category/new/:parentCategoryId', component: CategoryNewView },
  { path: 'category/edit/:categoryId', component: CategoryEditView },
  { path: 'picture/new', component: PictureNewView },
  { path: 'picture/new/:categoryId', component: PictureNewView },
  { path: '', redirectTo: 'home', pathMatch: 'full' },
  { path: '**', component: PageNotFoundView }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
