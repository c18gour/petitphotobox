import { Component, ViewChild, OnInit, ComponentFactoryResolver, ViewContainerRef } from '@angular/core';
import { Location } from '@angular/common';
import { Router } from '@angular/router';
import { environment as env } from '../../../environments/environment';

import { SessionError } from '../../core/exception/session-error';
import { AppTranslateService } from '../../core/i18n/app-translate-service';
import { ModalWindowSystem } from '../../modules/modal-window-system/modal-window-system';

import { UserSettingsController } from './controllers/user-settings-controller';
import { UserSettingsEntity } from './entities/user-settings-entity';

@Component({
  'selector': 'app-settings',
  'templateUrl': './user-settings-view.html',
  'styleUrls': ['./user-settings-view.scss']
})
export class UserSettingsView implements OnInit {
  entity: UserSettingsEntity;
  modal: ModalWindowSystem;
  hasChanged = false;

  constructor(
    private _controller: UserSettingsController,
    private _router: Router,
    private _location: Location,
    private _resolver: ComponentFactoryResolver,
    private _translate: AppTranslateService
  ) { }

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

  async goBack() {
    const message = await this._translate.get('dialog.discardChanges');

    if (!this.hasChanged || await this.modal.confirm(message)) {
      this._location.back();
    }
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
