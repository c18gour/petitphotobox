import { Component, Input, Output, EventEmitter } from '@angular/core';

@Component({
  selector: 'app-paginator',
  templateUrl: './paginator-component.html',
  styleUrls: ['./paginator-component.scss']
})
export class PaginatorComponent {
  @Input()
  page = 0;

  @Input()
  numPages = 0;

  @Output()
  selectPage = new EventEmitter<number>();

  get lastPage() {
    return this.numPages - 1;
  }

  prevPage() {
    this.selectPage.emit(Math.max(this.page - 1, 0));
  }

  nextPage() {
    this.selectPage.emit(Math.min(this.page + 1, this.numPages - 1));
  }
}
