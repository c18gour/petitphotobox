import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';

import { UserLoginController } from '../../controllers/user-login-controller';
import { UserLoginDocument } from '../../documents/user-login-document';

@Component({
  selector: 'app-login',
  templateUrl: './login-component.html',
  styleUrls: ['./login-component.scss']
})
export class UserLoginComponent implements OnInit {
  document: UserLoginDocument;
  errorMessage = '';

  constructor(
    private _router: Router,
    private _controller: UserLoginController) { }

  async ngOnInit() {
    this.document = await this._controller.get();
  }

  async onSubmit() {
    try {
      this.document = await this._controller.post(
        this.document.username, this.document.password);
    } catch (e) {
      this.errorMessage = e.message;
      throw e;
    }

    this._router.navigate(['/home']);
  }

}
