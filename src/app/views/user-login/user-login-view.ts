import { Component, OnInit, ViewChild, ViewContainerRef, ComponentFactoryResolver } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { Location } from '@angular/common';

import { SessionError } from '../../core/exception/session-error';
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
    private _router: Router,
    private _route: ActivatedRoute,
    private _location: Location,
    private _resolver: ComponentFactoryResolver) { }

  @ViewChild('modalContainer', { read: ViewContainerRef })
  modalContainer: ViewContainerRef;

  ngOnInit() {
    this.modal = new ModalWindowSystem(
      this, this._resolver, this.modalContainer);

    this._route.params.subscribe((params) => {
      this._goBack = params.back !== undefined;

      this.modal.loading(async () => {
        try {
          this.entity = await this._controller.get();
        } catch (e) {
          if (e instanceof SessionError) {
            this._router.navigate(['/home']);
          } else {
            this.modal.error(e.message);
          }

          throw e;
        }
      });
    });
  }

  async onSubmit() {
    this.modal.loading(async () => {
      try {
        this.entity = await this._controller.post(
          { username: this.entity.username, password: this.password });
      } catch (e) {
        this.password = '';
        this.modal.error(e.message);
        throw e;
      }

      if (this._goBack) {
        this._location.back();
      } else {
        this._router.navigate(['/home']);
      }
    });
  }
}
