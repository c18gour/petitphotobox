import { Component, Input, Output, EventEmitter } from '@angular/core';

import { environment as env } from '../../../../../environments/environment';

@Component({
  selector: 'app-home-picture',
  templateUrl: './picture-component.html',
  styleUrls: ['./picture-component.scss']
})
export class HomePictureComponent {
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
    return [env.apiUrl, this.path].join('/');
  }
}
