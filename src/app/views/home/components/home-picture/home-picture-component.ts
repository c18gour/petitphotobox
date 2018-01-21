import { Component, Input, Output, EventEmitter } from '@angular/core';

import { fullPath } from '../../../../core/utils';

@Component({
  selector: 'app-home-picture',
  templateUrl: './home-picture-component.html',
  styleUrls: ['./home-picture-component.scss']
})
export class HomePictureComponent {
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
  upPicture = new EventEmitter<string>();

  @Output()
  downPicture = new EventEmitter<string>();

  @Output()
  editPicture = new EventEmitter<string>();

  get fullPath(): string {
    return fullPath(this.path);
  }
}
