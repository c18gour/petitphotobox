import { Component, Input, Output, EventEmitter } from '@angular/core';

@Component({
  selector: 'app-entry',
  templateUrl: './entry-component.html',
  styleUrls: ['./entry-component.scss']
})
export class EntryComponent {
  @Input()
  entry;

  @Output()
  selectEntry = new EventEmitter<string>();

  onSelectEntry(categoryId: string) {
    this.selectEntry.emit(categoryId);
  }
}
