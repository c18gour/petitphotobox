import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

import { HomeView } from './views/home/home-view';
import { UserAccessView } from './views/user-access/user-access-view';
import { UserVerifyView } from './views/user-verify/user-verify-view';
import { CategoryNewView } from './views/category-new/category-new-view';
import { CategoryEditView } from './views/category-edit/category-edit-view';
import { PictureNewView } from './views/picture-new/picture-new-view';
import { PictureEditView } from './views/picture-edit/picture-edit-view';
import { SearchView } from './views/search/search-view';
import { PageNotFoundView } from './views/page-not-found/page-not-found-view';

const routes: Routes = [
  { path: 'home', component: HomeView },
  { path: 'home/:categoryId', component: HomeView },
  { path: 'home/:categoryId/:page', component: HomeView },
  { path: 'access', component: UserAccessView },
  { path: 'verify', component: UserVerifyView },
  // TODO: remove back button
  { path: 'login/:back', component: UserAccessView },
  { path: 'category/new', component: CategoryNewView },
  { path: 'category/new/:parentCategoryId', component: CategoryNewView },
  { path: 'category/edit/:categoryId', component: CategoryEditView },
  { path: 'picture/new', component: PictureNewView },
  { path: 'picture/new/:categoryId', component: PictureNewView },
  { path: 'picture/edit/:pictureId', component: PictureEditView },
  { path: 'search', component: SearchView },
  { path: 'search/:categoryIds', component: SearchView },
  { path: '', redirectTo: 'home', pathMatch: 'full' },
  { path: '**', component: PageNotFoundView }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
