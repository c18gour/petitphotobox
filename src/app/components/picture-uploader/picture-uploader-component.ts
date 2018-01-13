import { Component, OnInit, Input, Output, EventEmitter } from '@angular/core';
import { FileUploader } from 'ng2-file-upload';
import { fullPath } from '../../core/utils';

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
  error = new EventEmitter();

  ngOnInit() {
    const self = this;

    this.uploader = new FileUploader({
      url: fullPath('image-upload.php'),
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

    this.uploader.onErrorItem = () => {
      this.error.emit();
    };

    this.uploader.onCompleteAll = () => {
      this.state = 'initial';
    };
  }
}
