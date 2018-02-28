import { Component, OnInit, ViewChild, ViewContainerRef, ComponentFactoryResolver } from '@angular/core';
import { Router } from '@angular/router';

import { SessionError } from '../../core/exception/session-error';
import { ModalWindowSystem } from '../../modules/modal-window-system/modal-window-system';
import { UserAccessController } from './controllers/user-access-controller';
import { UserAccessEntity } from './entities/user-access-entity';
import { Url } from '../../core/url/url';

@Component({
  selector: 'app-user-access',
  templateUrl: './user-access-view.html',
  styleUrls: ['./user-access-view.scss']
})
export class UserAccessView implements OnInit {
  private _goBack = false;
  entity: UserAccessEntity;
  modal: ModalWindowSystem;
  password = '';

  constructor(
    private _controller: UserAccessController,
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
            this._router.navigate(['/access']);
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
