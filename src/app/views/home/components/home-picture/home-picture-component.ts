import { Component, Input, Output, EventEmitter } from '@angular/core';

import { environment as env } from '../../../../../environments/environment';
import { Url } from '../../../../core/url/url';

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

  get imagePath(): string {
    const url = Url.parse(this.path, env.apiUrl);

    return url.toString();
  }

  get smallImagePath(): string {
    const url = Url.parse(this.path, env.apiUrl);
    url.addParam('small');

    return url.toString();
  }
}
