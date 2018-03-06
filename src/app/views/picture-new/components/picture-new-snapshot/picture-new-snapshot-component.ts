import { Component, Input, Output, EventEmitter } from '@angular/core';

import { environment as env } from '../../../../../environments/environment';
import { Url } from '../../../../core/url/url';

@Component({
  selector: 'app-picture-new-snapshot',
  templateUrl: './picture-new-snapshot-component.html',
  styleUrls: ['./picture-new-snapshot-component.scss']
})
export class PictureNewSnapshotComponent {
  @Input()
  path: string;

  @Output()
  change = new EventEmitter();

  @Output()
  deletePicture = new EventEmitter<string>();

  @Output()
  upPicture = new EventEmitter<string>();

  @Output()
  downPicture = new EventEmitter<string>();

  get thumbImagePath(): string {
    const url = Url.parse(env.thumbsDir + this.path, env.apiUrl);

    return url.toString();
  }
}
