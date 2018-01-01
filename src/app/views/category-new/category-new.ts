import {
  Component, OnInit, ViewChild, ViewContainerRef, ComponentFactoryResolver
} from '@angular/core';
import { Location } from '@angular/common';
import { Router, ActivatedRoute } from '@angular/router';

import {
  CategoryNewController
} from '../../controllers/category-new-controller';
import { CategoryNewEntity } from '../../entities/category-new-entity';
import {
  InputTreeComponent
} from '../../components/input-tree/input-tree-component';
import {
  ModalWindowSystem
} from '../../modules/modal-window-system/modal-window-system';

@Component({
  selector: 'app-edit-category',
  templateUrl: './category-new.html',
  styleUrls: ['./category-new.scss']
})
export class CategoryNewView implements OnInit {
  entity: CategoryNewEntity;
  modal: ModalWindowSystem;

  constructor(
    private _controller: CategoryNewController,
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
      const parentCategoryId = params.parentCategoryId;

      this.modal.loading(async () => {
        try {
          this.entity = await this._controller.get({ parentCategoryId });
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
