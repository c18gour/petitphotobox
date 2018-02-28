import { Component, OnInit, ViewChild, ViewContainerRef, ComponentFactoryResolver } from '@angular/core';
import { Router } from '@angular/router';
import { environment as env } from '../../../environments/environment';

import { SessionError } from '../../core/exception/session-error';
import { ModalWindowSystem } from '../../modules/modal-window-system/modal-window-system';
import { UserRegisterCompleteController } from './controllers/user-register-complete-controller';
import { UserRegisterCompleteEntity } from './entities/user-register-complete-entity';

@Component({
  selector: 'app-user-complete',
  templateUrl: './user-register-complete-view.html',
  styleUrls: ['./user-register-complete-view.scss']
})
export class UserRegisterCompleteView implements OnInit {
  entity: UserRegisterCompleteEntity;
  modal: ModalWindowSystem;

  constructor(
    private _controller: UserRegisterCompleteController,
    private _resolver: ComponentFactoryResolver,
    private _router: Router) { }

  @ViewChild('modalContainer', { read: ViewContainerRef })
  modalContainer: ViewContainerRef;

  get languages() {
    return env.languages;
  }

  ngOnInit() {
    this.modal = new ModalWindowSystem(
      this, this._resolver, this.modalContainer);

    this.modal.loading(async () => {
      try {
        this.entity = await this._controller.get();
      } catch (e) {
        if (await this.modal.error(e.message)) {
          if (e instanceof SessionError) {
            this._router.navigate(['/login']);
          }
        }

        throw e;
      }
    });
  }

  onSubmit() {
    this.modal.loading(async () => {
      try {
        await this._controller.post(
          { name: this.entity.name, language: this.entity.language });
      } catch (e) {
        this.modal.error(e.message);
        throw e;
      }

      // backs to home
      window.location.href = '/';
    });
  }
}
