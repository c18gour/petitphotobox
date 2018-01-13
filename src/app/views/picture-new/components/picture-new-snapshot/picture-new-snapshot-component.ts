import { Component, Input, Output, EventEmitter } from '@angular/core';

import { fullPath } from '../../../../core/utils';

@Component({
  selector: 'app-picture-new-snapshot',
  templateUrl: './picture-new-snapshot-component.html',
  styleUrls: ['./picture-new-snapshot-component.scss']
})
export class PictureNewSnapshotComponent {
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
