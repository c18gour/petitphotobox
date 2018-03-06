import { Component, Input, Output, EventEmitter } from '@angular/core';

import { environment as env } from '../../../../../environments/environment';
import { Url } from '../../../../core/url/url';

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

  get imagePath(): string {
    const url = Url.parse(env.imagesDir + this.path, env.apiUrl);

    return url.toString();
  }

  get thumbImagePath(): string {
    const url = Url.parse(env.thumbsDir + this.path, env.apiUrl);

    return url.toString();
  }
}
