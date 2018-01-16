import { Component, OnInit, ViewChild, ViewContainerRef, ComponentFactoryResolver } from '@angular/core';
import { Location } from '@angular/common';
import { Router, ActivatedRoute } from '@angular/router';

import { SessionError } from '../../core/exception/session-error';
import { ModalWindowSystem } from '../../modules/modal-window-system/modal-window-system';
import { InputSelectComponent } from '../../components/input-select/input-select-component';
import { CategoryEditController } from './controllers/category-edit-controller';
import { CategoryEditEntity } from './entities/category-edit-entity';

@Component({
  selector: 'app-category-edit',
  templateUrl: './category-edit-view.html',
  styleUrls: ['./category-edit-view.scss']
})
export class CategoryEditView implements OnInit {
  entity: CategoryEditEntity;
  modal: ModalWindowSystem;
  hasChanged = false;
  private _categoryId: string;

  constructor(
    private _controller: CategoryEditController,
    private _router: Router,
    private _route: ActivatedRoute,
    private _location: Location,
    private _resolver: ComponentFactoryResolver
  ) { }

  @ViewChild('parentCategoryInput')
  parentCategoryInput: InputSelectComponent;

  @ViewChild('modalContainer', { read: ViewContainerRef })
  modalContainer: ViewContainerRef;

  ngOnInit() {
    this.modal = new ModalWindowSystem(
      this, this._resolver, this.modalContainer);

    this._route.params.subscribe((params) => {
      this._categoryId = params.categoryId;

      this.modal.loading(async () => {
        try {
          this.entity = await this._controller.get(
            { categoryId: this._categoryId });
        } catch (e) {
          if (await this.modal.error(e.message)) {
            if (e instanceof SessionError) {
              this._router.navigate(['/login']);
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
      try {
        this.entity = await this._controller.post({
          parentCategoryId: this.parentCategoryInput.value,
          categoryId: this._categoryId,
          title: this.entity.title
        });
      } catch (e) {
        this.modal.error(e.message);
        throw e;
      }

      this._router.navigate([`/home/${this.entity.id}`]);
    });
  }
}
