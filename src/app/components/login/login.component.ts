import { Component, OnInit } from '@angular/core';

import { LoginService } from '../../services/login.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent implements OnInit {
  username = '';
  password = '';

  constructor(private _controller: LoginService) { }

  ngOnInit() {
  }

  async onSubmit() {
    const res = await this._controller.post(this.username, this.password);
    console.log(res);
  }

}
