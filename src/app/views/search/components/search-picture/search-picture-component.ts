import { Component, Input, Output, EventEmitter } from '@angular/core';

import { fullPath } from '../../../../core/utils';

@Component({
  selector: 'app-search-picture',
  templateUrl: './search-picture-component.html',
  styleUrls: ['./search-picture-component.scss']
})
export class SearchPictureComponent {
  @Input()
  id: string;

  @Input()
  path: string;

  @Input()
  categories: number;

  @Input()
  snapshots: number;

  @Output()
  deletePicture = new EventEmitter<string>();

  @Output()
  editPicture = new EventEmitter<string>();

  get fullPath(): string {
    return fullPath(this.path);
  }
}
