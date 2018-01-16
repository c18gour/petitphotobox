import { Component, ViewChild, OnInit, ComponentFactoryResolver, ViewContainerRef } from '@angular/core';
import { Location } from '@angular/common';
import { Router, ActivatedRoute } from '@angular/router';

import { SessionError } from '../../core/exception/session-error';
import { ModalWindowSystem } from '../../modules/modal-window-system/modal-window-system';
import { InputCheckboxComponent } from '../../components/input-checkbox/input-checkbox-component';

import { PictureEditController } from './controllers/picture-edit-controller';
import { PictureEditEntity } from './entities/picture-edit-entity';
import { SortableList } from '../../core/model/sortable-list';

@Component({
  selector: 'app-picture-edit-view',
  templateUrl: './picture-edit-view.html',
  styleUrls: ['./picture-edit-view.scss']
})
export class PictureEditView implements OnInit {
  entity: PictureEditEntity;
  modal: ModalWindowSystem;
  showMoreOptions = false;
  paths = new SortableList<string>();
  hasChanged = false;

  constructor(
    private _controller: PictureEditController,
    private _router: Router,
    private _route: ActivatedRoute,
    private _location: Location,
    private _resolver: ComponentFactoryResolver
  ) { }

  @ViewChild('categoriesInput')
  categoriesInput: InputCheckboxComponent;

  @ViewChild('modalContainer', { read: ViewContainerRef })
  modalContainer: ViewContainerRef;

  async ngOnInit() {
    this.modal = new ModalWindowSystem(
      this, this._resolver, this.modalContainer);

    this._route.params.subscribe((params) => {
      const pictureId = params.pictureId;

      this.modal.loading(async () => {
        try {
          this.entity = await this._controller.get({ pictureId });
          this.paths = new SortableList<string>(this.entity.snapshots);
        } catch (e) {
          if (await this.modal.error(e.message)) {
            if (e instanceof SessionError) {
              this._router.navigate(['/login/back']);
            }
          }

          throw e;
        }
      });
    });
  }

  async goBack() {
    if (!this.hasChanged || await this.modal.confirm('Discard changes?')) {
      this._location.back();
    }
  }

  onSubmit() {
    this.modal.loading(async () => {
      const categoryIds = this.categoriesInput.value;
      const pictureId = this.entity.id;

      if (categoryIds.length === 0) {
        this.modal.error('Select at least a category');
        return;
      }

      try {
        this.entity = await this._controller.post({
          pictureId,
          categoryIds,
          title: this.entity.title,
          tags: this.entity.tags,
          snapshots: this.paths.items.reverse()
        });
      } catch (e) {
        this.modal.error(e.message);
        throw e;
      }

      this._location.back();
    });
  }
}
