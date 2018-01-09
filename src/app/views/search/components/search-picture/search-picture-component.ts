import { Component, Input, Output, EventEmitter } from '@angular/core';

import { environment as env } from '../../../../../environments/environment';

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

  @Output()
  deletePicture = new EventEmitter<string>();

  @Output()
  editPicture = new EventEmitter<string>();

  // TODO: create App.fullPath
  get fullPath(): string {
    return [env.apiUrl, this.path].join('/');
  }
}
