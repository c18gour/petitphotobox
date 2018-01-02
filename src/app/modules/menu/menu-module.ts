import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { MenuComponent } from './menu-component';
import { MenuEntryComponent } from './components/menu-entry-component';

@NgModule({
  imports: [
    CommonModule
  ],
  declarations: [
    MenuComponent,
    MenuEntryComponent
  ],
  exports: [
    MenuComponent
  ]
})
export class MenuModule { }
