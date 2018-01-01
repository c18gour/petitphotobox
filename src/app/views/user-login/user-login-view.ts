import {
  Component, OnInit, ViewChild, ViewContainerRef, ComponentFactoryResolver
} from '@angular/core';
import { Router } from '@angular/router';

import { UserLoginController } from '../../controllers/user-login-controller';
import { UserLoginEntity } from '../../entities/user-login-entity';
import { SessionError } from '../../core/exception/session-error';
import { ModalWindowSystem } from '../../core/modal/modal-window-system';

@Component({
  selector: 'app-user-login',
  templateUrl: './user-login-view.html',
  styleUrls: ['./user-login-view.scss']
})
export class UserLoginView implements OnInit {
  isRequesting = false;
  entity: UserLoginEntity;
  errorMessage = '';
  modal: ModalWindowSystem;

  constructor(
    private _controller: UserLoginController,
    private _router: Router,
    private _resolver: ComponentFactoryResolver) { }

  @ViewChild('modalContainer', { read: ViewContainerRef })
  modalContainer: ViewContainerRef;

  async ngOnInit() {
    this.modal = new ModalWindowSystem(this._resolver, this.modalContainer);

    this.isRequesting = true;
    try {
      this.entity = await this._controller.get();
    } catch (e) {
      if (e instanceof SessionError) {
        this._router.navigate(['/home']);
      }
    } finally {
      this.isRequesting = false;
    }
  }

  async onSubmit() {
    this.isRequesting = true;
    try {
      this.entity = await this._controller.post(
        { username: this.entity.username, password: this.entity.password });
    } catch (e) {
      this.modal.error(e.message);
      throw e;
    } finally {
      this.isRequesting = false;
    }

    this._router.navigate(['/home']);
  }
}
