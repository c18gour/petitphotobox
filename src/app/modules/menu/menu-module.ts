import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';

import { MenuComponent } from './components/menu-component';
import { EntryComponent } from './components/entry-component';

@NgModule({
  imports: [
    CommonModule,
    RouterModule
  ],
  declarations: [
    MenuComponent,
    EntryComponent
  ],
  exports: [
    MenuComponent,
    RouterModule
  ]
})
export class MenuModule { }
