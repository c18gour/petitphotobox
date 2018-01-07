import {
  Component, ViewChild, OnInit, ComponentFactoryResolver, ViewContainerRef
} from '@angular/core';
import { Location } from '@angular/common';
import { Router, ActivatedRoute } from '@angular/router';

import {
  ModalWindowSystem
} from '../../modules/modal-window-system/modal-window-system';
import {
  InputSelectComponent
} from '../../components/input-select/input-select-component';

import { PictureNewController } from './controllers/picture-new-controller';
import { PictureNewEntity } from './entities/picture-new-entity';

@Component({
  selector: 'app-picture-new',
  templateUrl: './picture-new.html',
  styleUrls: ['./picture-new.scss']
})
export class PictureNewView implements OnInit {
  entity: PictureNewEntity;
  modal: ModalWindowSystem;

  constructor(
    private _controller: PictureNewController,
    private _router: Router,
    private _route: ActivatedRoute,
    private _location: Location,
    private _resolver: ComponentFactoryResolver
  ) { }

  @ViewChild('categoryInput')
  parentCategoryInput: InputSelectComponent;

  @ViewChild('modalContainer', { read: ViewContainerRef })
  modalContainer: ViewContainerRef;

  async ngOnInit() {
    this.modal = new ModalWindowSystem(
      this, this._resolver, this.modalContainer);

    this._route.params.subscribe((params) => {
      const categoryId = params.categoryId;

      this.modal.loading(async () => {
        try {
          this.entity = await this._controller.get({ categoryId });
        } catch (e) {
          this.modal.error(e.message);
          throw e;
        }
      });
    });
  }
}
