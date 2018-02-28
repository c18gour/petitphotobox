import { Component, OnInit, ViewChild, ViewContainerRef, ComponentFactoryResolver } from '@angular/core';
import { Location } from '@angular/common';
import { Router, ActivatedRoute } from '@angular/router';

import { SessionError } from '../../core/exception/session-error';
import { AppTranslateService } from '../../core/i18n/app-translate-service';
import { CategoryNewController } from './controllers/category-new-controller';
import { CategoryNewEntity } from './entities/category-new-entity';
import { InputSelectComponent } from '../../components/input-select/input-select-component';
import { ModalWindowSystem } from '../../modules/modal-window-system/modal-window-system';

@Component({
  selector: 'app-edit-category',
  templateUrl: './category-new-view.html',
  styleUrls: ['./category-new-view.scss']
})
export class CategoryNewView implements OnInit {
  entity: CategoryNewEntity;
  modal: ModalWindowSystem;
  hasChanged = false;

  constructor(
    private _controller: CategoryNewController,
    private _router: Router,
    private _route: ActivatedRoute,
    private _location: Location,
    private _resolver: ComponentFactoryResolver,
    private _translate: AppTranslateService
  ) { }

  @ViewChild('parentCategoryInput')
  parentCategoryInput: InputSelectComponent;

  @ViewChild('modalContainer', { read: ViewContainerRef })
  modalContainer: ViewContainerRef;

  async ngOnInit() {
    this.modal = new ModalWindowSystem(
      this, this._resolver, this.modalContainer);

    this._route.params.subscribe((params) => {
      const parentCategoryId = params.parentCategoryId;

      this.modal.loading(async () => {
        try {
          this.entity = await this._controller.get({ parentCategoryId });
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
    const message = await this._translate.get('dialog.discardChanges');

    if (!this.hasChanged || await this.modal.confirm(message)) {
      this._location.back();
    }
  }

  async onSubmit() {
    this.modal.loading(async () => {
      try {
        this.entity = await this._controller.post({
          parentCategoryId: this.parentCategoryInput.value,
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
