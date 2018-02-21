import { Component, OnInit, ViewChild, ComponentFactoryResolver, ViewContainerRef } from '@angular/core';
import { Router } from '@angular/router';

import { ModalWindowSystem } from '../../modules/modal-window-system/modal-window-system';
import { AppTranslateService } from '../../core/i18n/app-translate-service';

@Component({
  selector: 'app-page-not-found',
  templateUrl: './page-not-found-view.html',
  styleUrls: ['./page-not-found-view.scss']
})
export class PageNotFoundView implements OnInit {
  modal: ModalWindowSystem;

  constructor(
    private _router: Router,
    private _resolver: ComponentFactoryResolver,
    private _translate: AppTranslateService) {
  }

  @ViewChild('modalContainer', { read: ViewContainerRef })
  modalContainer: ViewContainerRef;

  async ngOnInit() {
    this.modal = new ModalWindowSystem(
      this, this._resolver, this.modalContainer);

    const message = await this._translate.get('page-not-found');
    if (await this.modal.alert(message)) {
      this._router.navigate(['/home']);
    }
  }

}
