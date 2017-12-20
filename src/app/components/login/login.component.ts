import { Component, OnInit } from '@angular/core';

import { LoginController } from '../../controllers/login-controller';
import { LoginDocument } from '../../documents/login-document';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent implements OnInit {
  model: LoginDocument;

  constructor(private _controller: LoginController) { }

  async ngOnInit() {
    this.model = await this._controller.get();
    console.log(this.model);
  }

  async onSubmit() {
    this.model = await this._controller.post(this.model);
    console.log(this.model);
  }

}
