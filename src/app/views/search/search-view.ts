import { Component, OnInit, ViewChild, ViewContainerRef, ComponentFactoryResolver } from '@angular/core';
import { Location } from '@angular/common';
import { Router, ActivatedRoute } from '@angular/router';

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
  type = 'any';
  recurse = false;
  fromDate = '';
  toDate = '';

  constructor(
    private _controller: SearchController,
    private _router: Router,
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
      const categoryIds = params.categoryIds
        ? params.categoryIds.split(',') : '';
      const page = params.page || 0;
      this.type = params.type || 'any';
      this.recurse = params.recurse === 'true';
      this.fromDate = params.fromDate || '';
      this.toDate = params.toDate || '';

      this.modal.loading(async () => {
        try {
          this.entity = await this._controller.get({
            categoryIds,
            page,
            type: this.type,
            recurse: this.recurse,
            fromDate: this.fromDate,
            toDate: this.toDate
          });
        } catch (e) {
          this.modal.error(e.message);
          throw e;
        }
      });
    });
  }

  onSubmit() {
    this.goPage(0);
  }

  goPage(page: number) {
    const categoryIds = this.categoriesInput.value;

    if (categoryIds.length === 0) {
      this.modal.error('Please select a category');
      return;
    }

    this._router.navigate([
      `/search/${categoryIds}/${page}/${this.type}/${this.recurse}` +
      `/${this.fromDate}/${this.toDate}`
    ]);
  }

  deletePicture(pictureId: string) {
    console.log(pictureId);
  }
}
