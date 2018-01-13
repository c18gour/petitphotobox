import { Component, OnInit, ViewChild, ComponentFactoryResolver, ViewContainerRef } from '@angular/core';
import { Router } from '@angular/router';

import { ModalWindowSystem } from '../../modules/modal-window-system/modal-window-system';

@Component({
  selector: 'app-page-not-found',
  templateUrl: './page-not-found-view.html',
  styleUrls: ['./page-not-found-view.scss']
})
export class PageNotFoundView implements OnInit {
  modal: ModalWindowSystem;

  constructor(
    private _router: Router,
    private _resolver: ComponentFactoryResolver) { }

  @ViewChild('modalContainer', { read: ViewContainerRef })
  modalContainer: ViewContainerRef;

  async ngOnInit() {
    this.modal = new ModalWindowSystem(
      this, this._resolver, this.modalContainer);

    if (await this.modal.alert('Page not found.\nRedirect to Home')) {
      this._router.navigate(['/home']);
    }
  }

}
