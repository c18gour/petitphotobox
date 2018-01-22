import { Component, OnInit, ViewChild, ViewContainerRef, ComponentFactoryResolver } from '@angular/core';
import { Location } from '@angular/common';
import { Router, ActivatedRoute } from '@angular/router';

import { SessionError } from '../../core/exception/session-error';
import { ModalWindowSystem } from '../../modules/modal-window-system/modal-window-system';
import { InputCheckboxComponent } from '../../components/input-checkbox/input-checkbox-component';

import { SearchController } from './controllers/search-controller';
import { PictureDeleteController } from './controllers/picture-delete-controller';
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
    private _deleteController: PictureDeleteController,
    private _router: Router,
    private _route: ActivatedRoute,
    private _location: Location,
    private _resolver: ComponentFactoryResolver
  ) { }

  @ViewChild('categoriesInput')
  categoriesInput: InputCheckboxComponent;

  @ViewChild('modalContainer', { read: ViewContainerRef })
  modalContainer: ViewContainerRef;

  ngOnInit() {
    this.modal = new ModalWindowSystem(
      this, this._resolver, this.modalContainer);

    this._route.params.subscribe((params) => {
      const categoryIds = params.categoryIds
        ? params.categoryIds.split(',') : '';

      this.modal.loading(async () => {
        try {
          this.entity = await this._controller.get({
            categoryIds,
            type: this.type,
            recurse: this.recurse
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
  }

  goBack() {
    this._location.back();
  }

  deletePicture(pictureId: string) {
    this.modal.loading(async () => {
      try {
        await this._deleteController.post({ pictureId });
      } catch (e) {
        this.modal.error(e.message);
        throw e;
      }

      this.goPage(this.entity.page);
    });
  }

  editPicture(pictureId: string) {
    this._router.navigate([`/picture/edit/${pictureId}`]);
  }
}
