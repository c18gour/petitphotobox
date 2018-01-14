import { Component, Input, Output, EventEmitter } from '@angular/core';

import { fullPath } from '../../../../core/utils';

@Component({
  selector: 'app-picture-edit-snapshot',
  templateUrl: './picture-edit-snapshot-component.html',
  styleUrls: ['./picture-edit-snapshot-component.scss']
})
export class PictureEditSnapshotComponent {
  @Input()
  path: string;

  @Output()
  deletePicture = new EventEmitter<string>();

  @Output()
  upPicture = new EventEmitter<string>();

  @Output()
  downPicture = new EventEmitter<string>();

  get fullPath(): string {
    return fullPath(this.path);
  }
}
