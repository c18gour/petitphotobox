import { Component, OnInit, ViewChild, ViewContainerRef, ComponentFactoryResolver } from '@angular/core';
import { Router } from '@angular/router';

import { SessionError } from '../../core/exception/session-error';
import { UserRegisterController } from './controllers/user-register-controller';
import { ModalWindowSystem } from '../../modules/modal-window-system/modal-window-system';
import { Url } from '../../core/url/url';

@Component({
  selector: 'app-user-register',
  templateUrl: './user-register-view.html',
  styleUrls: ['./user-register-view.scss']
})
export class UserRegisterView implements OnInit {
  modal: ModalWindowSystem;

  constructor(
    private _controller: UserRegisterController,
    private _resolver: ComponentFactoryResolver,
    private _router: Router
  ) { }

  @ViewChild('modalContainer', { read: ViewContainerRef })
  modalContainer: ViewContainerRef;

  ngOnInit() {
    const info = Url.parse(window.location.href);
    const params = info.params;

    this.modal = new ModalWindowSystem(
      this, this._resolver, this.modalContainer);

    this.modal.loading(async () => {
      try {
        await this._controller.get({ code: params.code, state: params.state });
      } catch (e) {
        if (await this.modal.error(e.message)) {
          if (e instanceof SessionError) {
            this._router.navigate(['/login']);
          }
        }

        throw e;
      }

      this._router.navigate(['/home']);
    });
  }
}
