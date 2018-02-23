import { Component, OnInit, ViewChild, ViewContainerRef, ComponentFactoryResolver } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';

import { SessionError } from '../../core/exception/session-error';
import { HomeEntity, HomePictureEntity } from './entities/home-entity';
import { MenuComponent } from '../../modules/menu/menu-component';
import { ModalWindowSystem } from '../../modules/modal-window-system/modal-window-system';
import { AppTranslateService } from '../../core/i18n/app-translate-service';

// controllers
import { LogoutController } from './../../controllers/logout-controller';
import { HomeController } from './controllers/home-controller';
import { CategoryDeleteController } from './controllers/category-delete-controller';
import { CategoryPictureDeleteController } from './controllers/category-picture-delete-controller';
import { CategoryPictureUpController } from './controllers/category-picture-up-controller';
import { CategoryPictureDownController } from './controllers/category-picture-down-controller';

@Component({
  selector: 'app-home',
  templateUrl: './home-view.html',
  styleUrls: ['./home-view.scss', './home-view-tablet.scss']
})
export class HomeView implements OnInit {
  entity: HomeEntity;
  modal: ModalWindowSystem;
  categoryId: string;
  page = 0;

  constructor(
    private _controller: HomeController,
    private _logoutController: LogoutController,
    private _categoryDeleteController: CategoryDeleteController,
    private _pictureDeleteController: CategoryPictureDeleteController,
    private _pictureUpController: CategoryPictureUpController,
    private _pictureDownController: CategoryPictureDownController,
    private _router: Router,
    private _route: ActivatedRoute,
    private _resolver: ComponentFactoryResolver,
    private _translate: AppTranslateService
  ) { }

  @ViewChild('menu')
  menu: MenuComponent;

  @ViewChild('modalContainer', { read: ViewContainerRef })
  modalContainer: ViewContainerRef;

  ngOnInit() {
    this.modal = new ModalWindowSystem(
      this, this._resolver, this.modalContainer);

    this._route.params.subscribe(async (params) => {
      this.categoryId = params.categoryId ? params.categoryId : '';
      this.page = params.page ? parseInt(params.page, 10) : 0;

      this.modal.loading(() => this._refresh());
    });
  }

  async confirmExit() {
    const message = await this._translate.get('home.systemExit');

    if (await this.modal.confirm(message)) {
      await this._logoutController.post();
      this._router.navigate(['/access']);
    }
  }

  onSelectEntry(categoryId: string) {
    this.menu.visible = false;
    this._router.navigate(['/home', categoryId]);
  }

  async confirmDeleteCategory() {
    const message = await this._translate.get('dialog.areYouSure');

    if (await this.modal.confirm(message)) {
      this.modal.loading(async () => {
        try {
          await this._categoryDeleteController.post(
            { categoryId: this.entity.id });
        } catch (e) {
          this.modal.error(e.message);
          throw e;
        }

        this._router.navigate(['/home']);
      });
    }
  }

  deletePicture(pictureId: string) {
    this.modal.loading(async () => {
      try {
        await this._pictureDeleteController.post({
          categoryId: this.categoryId, pictureId
        });
      } catch (e) {
        this.modal.error(e.message);
        throw e;
      }

      this._refresh();
    });
  }

  movePictureUp(pictureId: string) {
    this.modal.loading(async () => {
      try {
        await this._pictureUpController.post({
          categoryId: this.categoryId, pictureId
        });
      } catch (e) {
        this.modal.error(e.message);
        throw e;
      }

      this._refresh(pictureId);
    });
  }

  movePictureDown(pictureId: string) {
    this.modal.loading(async () => {
      try {
        await this._pictureDownController.post({
          categoryId: this.categoryId, pictureId
        });
      } catch (e) {
        this.modal.error(e.message);
        throw e;
      }

      this._refresh(pictureId);
    });
  }

  editPicture(id: string) {
    this._router.navigate([`/picture/edit/${id}`]);
  }

  goPage(page: number) {
    this._router.navigate([`/home/${this.categoryId}/${page}`]);
  }

  trackPicture(index: number, item: HomePictureEntity) {
    return item.id;
  }

  private async _refresh(pictureId: string = '') {
    try {
      this.entity = await this._controller.get(
        { categoryId: this.categoryId, page: this.page, pictureId });
    } catch (e) {
      if (await this.modal.error(e.message)) {
        if (e instanceof SessionError) {
          this._router.navigate(['/access']);
        }
      }

      throw e;
    }
  }
}
