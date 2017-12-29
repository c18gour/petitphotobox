import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';

import { UserLoginController } from '../../controllers/user-login-controller';
import { UserLoginEntity } from '../../entities/user-login-entity';
import { SessionError } from '../../core/exception/session-error';

@Component({
  selector: 'app-user-login',
  templateUrl: './user-login-view.html',
  styleUrls: ['./user-login-view.scss']
})
export class UserLoginView implements OnInit {
  entity: UserLoginEntity;
  errorMessage = '';

  constructor(
    private _controller: UserLoginController,
    private _router: Router) { }

  async ngOnInit() {
    try {
      this.entity = await this._controller.get();
    } catch (e) {
      if (e instanceof SessionError) {
        this._router.navigate(['/home']);
      }
    }
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
