import { Component, OnInit, ViewChild, ViewContainerRef, ComponentFactoryResolver } from '@angular/core';

import { ModalWindowSystem } from '../../modules/modal-window-system/modal-window-system';
import { UserAccessController } from './controllers/user-access-controller';
import { UserAccessEntity } from './entities/user-access-entity';

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
