import { Component, OnInit, ViewChild, ViewContainerRef, ComponentFactoryResolver } from '@angular/core';
import { Location } from '@angular/common';
import { Router } from '@angular/router';

import { SessionError } from '../../core/exception/session-error';
import { ModalWindowSystem } from '../../modules/modal-window-system/modal-window-system';
import { InputCheckboxComponent } from '../../components/input-checkbox/input-checkbox-component';

import { SearchController } from './controllers/search-controller';
import { SearchEntity } from './entities/search-entity';

@Component({
  selector: 'app-search',
  templateUrl: './search-view.html',
  styleUrls: ['./search-view.scss']
})
export class SearchView implements OnInit {
  entity: SearchEntity;
  modal: ModalWindowSystem;

  constructor(
    private _controller: SearchController,
    private _router: Router,
    private _location: Location,
    private _resolver: ComponentFactoryResolver
  ) { }

  @ViewChild('categoriesInput')
  categoriesInput: InputCheckboxComponent;

  @ViewChild('modalContainer', { read: ViewContainerRef })
  modalContainer: ViewContainerRef;

  goBack() {
    this._location.back();
  }

  ngOnInit() {
    this.modal = new ModalWindowSystem(
      this, this._resolver, this.modalContainer);

    this.modal.loading(async () => {
      try {
        this.entity = await this._controller.get();
      } catch (e) {
        this.modal.error(e.message);
        throw e;
      }
    });
  }

  onSubmit() {
    this.modal.loading(async () => {
      const categoryIds = this.categoriesInput.value;

      try {
        this.entity = await this._controller.post({ categoryIds });
      } catch (e) {
        if (await this.modal.error(e.message)) {
          if (e instanceof SessionError) {
            this._router.navigate(['/login/back']);
          }
        }

        throw e;
      }
    });
  }

  goPage(page: number) {
    this.modal.loading(async () => {
      const categoryIds = this.categoriesInput.value;

      try {
        this.entity = await this._controller.post({ categoryIds, page });
      } catch (e) {
        if (await this.modal.error(e.message)) {
          if (e instanceof SessionError) {
            this._router.navigate(['/login/back']);
          }
        }

        throw e;
      }
    });
  }

  deletePicture(pictureId: string) {
    console.log(pictureId);
  }
}
