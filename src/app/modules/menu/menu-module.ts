// TODO: entities, components, etc...
import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { MenuComponent } from './components/menu-component';
import { EntryComponent } from './components/entry-component';

@NgModule({
  imports: [
    CommonModule
  ],
  declarations: [
    MenuComponent,
    EntryComponent
  ],
  exports: [
    MenuComponent
  ]
})
export class MenuModule { }
