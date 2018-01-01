import {
  Component, OnInit, ViewChild, ViewContainerRef, ComponentFactoryResolver
} from '@angular/core';
import { Location } from '@angular/common';
import { Router, ActivatedRoute } from '@angular/router';

import {
  CategoryEditController
} from '../../controllers/category-edit-controller';
import { CategoryNewEntity } from '../../entities/category-new-entity';
import {
  InputTreeComponent
} from '../../components/input-tree/input-tree-component';
import {
  ModalWindowSystem
} from '../../modules/modal-window-system/modal-window-system';

@Component({
  selector: 'app-category-edit',
  templateUrl: './category-edit.html',
  styleUrls: ['./category-edit.scss']
})
export class CategoryEditView implements OnInit {
  entity: CategoryNewEntity;
  modal: ModalWindowSystem;
  private _categoryId: string;

  constructor(
    private _controller: CategoryEditController,
    private _router: Router,
    private _route: ActivatedRoute,
    private _location: Location,
    private _resolver: ComponentFactoryResolver
  ) { }

  @ViewChild('parentCategoryInput')
  parentCategoryInput: InputTreeComponent;

  @ViewChild('modalContainer', { read: ViewContainerRef })
  modalContainer: ViewContainerRef;

  async ngOnInit() {
    this.modal = new ModalWindowSystem(
      this, this._resolver, this.modalContainer);

    this._route.params.subscribe((params) => {
      this._categoryId = params.categoryId;

      this.modal.loading(async () => {
        try {
          this.entity = await this._controller.get(
            { categoryId: this._categoryId });
        } catch (e) {
          this.modal.error(e.message);
          throw e;
        }
      });
    });
  }

  goBack() {
    this._location.back();
  }

  async onSubmit() {
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
