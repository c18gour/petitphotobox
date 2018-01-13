import { Component, OnInit, ViewChild, ViewContainerRef, ComponentFactoryResolver } from '@angular/core';
import { Location } from '@angular/common';
import { ActivatedRoute } from '@angular/router';

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
    private _route: ActivatedRoute,
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

    this._route.params.subscribe((params) => {
      const categoryId = params.categoryId;

      this.modal.loading(async () => {
        try {
          this.entity = await this._controller.get({
            categoryIds: [categoryId]
          });
        } catch (e) {
          this.modal.error(e.message);
          throw e;
        }
      });
    });
  }

  onSubmit() {
    this.modal.loading(async () => {
      const categoryIds = this.categoriesInput.value;

      try {
        this.entity = await this._controller.post({ categoryIds });
      } catch (e) {
        this.modal.error(e.message);
        throw e;
      }
    });
  }
}
