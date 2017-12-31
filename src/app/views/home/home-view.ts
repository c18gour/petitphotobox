import { Component, OnInit, ViewChild } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';

import { SessionError } from '../../core/exception/session-error';
import { MenuComponent } from '../../modules/menu/components/menu-component';
import { HomeController } from '../../controllers/home-controller';
import { LogoutController } from '../../controllers/logout-controller';
import { HomeEntity } from '../../entities/home-entity';

@Component({
  selector: 'app-home',
  templateUrl: './home-view.html',
  styleUrls: ['./home-view.scss']
})
export class HomeView implements OnInit {
  entity: HomeEntity;
  isRequesting = false;
  errorMessage = '';
  categoryId = '';

  constructor(
    private _controller: HomeController,
    private _logoutController: LogoutController,
    private _router: Router,
    private _route: ActivatedRoute
  ) { }

  @ViewChild('menu')
  menu: MenuComponent;

  ngOnInit() {
    this._route.params.subscribe(async (params) => {
      this.categoryId = params.id ? params.id : '';

      this.isRequesting = true;
      try {
        this.entity = await this._controller.get({
          categoryId: this.categoryId
        });
      } catch (e) {
        if (e instanceof SessionError) {
          this._router.navigate(['/login']);
        } else {
          this.errorMessage = e.message;
        }

        throw e;
      } finally {
        this.isRequesting = false;
      }
    });
  }

  async logout() {
    this.isRequesting = true;
    try {
      await this._logoutController.post();
    } finally {
      this.isRequesting = false;
    }

    this._router.navigate(['/login']);
  }

  onSelectEntry(categoryId: string) {
    this.menu.visible = false;
    this._router.navigate(['/home', categoryId]);
  }

  goHome() {
    this._router.navigate(['/home']);
  }
}
