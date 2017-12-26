import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';

import { UserLoginController } from '../../controllers/user-login-controller';
import { UserLoginEntity } from '../../entities/user-login-entity';

@Component({
  selector: 'app-login',
  templateUrl: './login-component.html',
  styleUrls: ['./login-component.scss']
})
export class UserLoginComponent implements OnInit {
  entity: UserLoginEntity;
  errorMessage = '';

  constructor(
    private _controller: UserLoginController,
    private _router: Router) { }

  async ngOnInit() {
    // TODO: if the user has already logged, redirect to home
    // TODO: check error status
    this.entity = await this._controller.get();
  }

  async onSubmit() {
    try {
      this.entity = await this._controller.post(
        { username: this.entity.username, password: this.entity.password });
    } catch (e) {
      this.errorMessage = e.message;
      throw e;
    }

    this._router.navigate(['/home']);
  }

}
