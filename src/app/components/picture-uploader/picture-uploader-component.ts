import { Component, OnInit, Input, Output, EventEmitter } from '@angular/core';
import { FileUploader } from 'ng2-file-upload';

import { environment as env } from '../../../environments/environment';
import { Url } from '../../core/url/url';

@Component({
  selector: 'app-picture-uploader',
  templateUrl: './picture-uploader-component.html',
  styleUrls: ['./picture-uploader-component.scss']
})
export class PictureUploaderComponent implements OnInit {
  private readonly _defaultStroke = 15;
  uploader: FileUploader;
  current = 0;
  state: 'initial' | 'uploading' = 'initial';

  @Output()
  success = new EventEmitter<string>();

  @Output()
  error = new EventEmitter<string>();

  ngOnInit() {
    const self = this;
    const url = Url.parse('image-upload.php', env.apiUrl);

    this.uploader = new FileUploader({
      url: url.toString(),
      authToken: 'Authorization',
      autoUpload: true
    });

    this.uploader.onBeforeUploadItem = (item) => {
      this.state = 'uploading';
    };

    this.uploader.onProgressItem = (item, progress) => {
      this.current = progress;
    };

    this.uploader.onSuccessItem = (item, response) => {
      const document = JSON.parse(response);

      this.success.emit(document.body.path);
    };

    this.uploader.onErrorItem = (item, response, status, headers) => {
      const document = JSON.parse(response);

      this.error.emit(document.status.message);
    };

    this.uploader.onCompleteAll = () => {
      this.state = 'initial';
    };
  }

  onInputChange(input: HTMLInputElement) {
    input.value = '';
  }
}
