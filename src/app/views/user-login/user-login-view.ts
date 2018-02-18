import { Component, OnInit, ViewChild, ViewContainerRef, ComponentFactoryResolver } from '@angular/core';

import { ModalWindowSystem } from '../../modules/modal-window-system/modal-window-system';
import { UserLoginController } from './controllers/user-login-controller';
import { UserLoginEntity } from './entities/user-login-entity';

@Component({
  selector: 'app-user-login',
  templateUrl: './user-login-view.html',
  styleUrls: ['./user-login-view.scss']
})
export class UserLoginView implements OnInit {
  private _goBack = false;
  entity: UserLoginEntity;
  modal: ModalWindowSystem;
  password = '';

  constructor(
    private _controller: UserLoginController,
    private _resolver: ComponentFactoryResolver) { }

  @ViewChild('modalContainer', { read: ViewContainerRef })
  modalContainer: ViewContainerRef;

  ngOnInit() {
    this.modal = new ModalWindowSystem(
      this, this._resolver, this.modalContainer);

    this.modal.loading(async () => {
      this.entity = await this._controller.get();
    });
  }
}
