import { Component, Input, Output, EventEmitter } from '@angular/core';

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
}
