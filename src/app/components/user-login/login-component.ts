import { Component, OnInit } from '@angular/core';

import { UserLoginController } from '../../controllers/user-login-controller';
import { UserLoginDocument } from '../../documents/user-login-document';

@Component({
  selector: 'app-login',
  templateUrl: './login-component.html',
  styleUrls: ['./login-component.scss']
})
export class UserLoginComponent implements OnInit {
  document: UserLoginDocument;

  constructor(private _controller: UserLoginController) { }

  async ngOnInit() {
    this.document = await this._controller.get();
  }

  async onSubmit() {
    this.document = await this._controller.post(
      this.document.username, this.document.password);
    console.log(this.document);
  }

}
