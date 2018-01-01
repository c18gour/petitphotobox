import { Component, Input } from '@angular/core';

@Component({
  selector: 'app-picture',
  templateUrl: './picture-component.html',
  styleUrls: ['./picture-component.scss']
})
export class PictureComponent {

  @Input()
  path: string;
}
