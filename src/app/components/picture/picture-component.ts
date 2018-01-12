import { Component, Input, Output, EventEmitter } from '@angular/core';

import { fullPath } from '../../core/utils';

@Component({
  selector: 'app-picture',
  templateUrl: './picture-component.html',
  styleUrls: ['./picture-component.scss']
})
export class PictureComponent {
  @Input()
  id: string;

  @Input()
  path: string;

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
