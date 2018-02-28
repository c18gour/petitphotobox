import { Component, OnInit, ViewChild, ViewContainerRef, ComponentFactoryResolver } from '@angular/core';
import { Router } from '@angular/router';

import { SessionError } from '../../core/exception/session-error';
import { ModalWindowSystem } from '../../modules/modal-window-system/modal-window-system';
import { UserLoginController } from './controllers/user-login-controller';
import { UserLoginEntity } from './entities/user-login-entity';
import { Url } from '../../core/url/url';

@Component({
  selector: 'app-user-login',
  templateUrl: './user-login-view.html',
  styleUrls: ['./user-login-view.scss']
})
export class UserLoginView implements OnInit {
  entity: UserLoginEntity;
  modal: ModalWindowSystem;

  constructor(
    private _controller: UserLoginController,
    private _resolver: ComponentFactoryResolver,
    private _router: Router) { }

  @ViewChild('modalContainer', { read: ViewContainerRef })
  modalContainer: ViewContainerRef;

  ngOnInit() {
    const info = Url.parse(window.location.href);
    const params = info.params;

    this.modal = new ModalWindowSystem(
      this, this._resolver, this.modalContainer);

    this.modal.loading(async () => {
      try {
        this.entity = await this._controller.get(
          { code: params.code, state: params.state });
      } catch (e) {
        if (await this.modal.error(e.message)) {
          if (e instanceof SessionError) {
            this._router.navigate(['/login']);
          }
        }

        throw e;
      }

      if (params.code || params.state) {
        this._router.navigate(['/home']);
      }
    });
  }
}
