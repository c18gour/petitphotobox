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
      const categoryId = params.id ? params.id : '';

      try {
        this.entity = await this._controller.get({ categoryId });
      } catch (e) {
        if (e instanceof SessionError) {
          this._router.navigate(['/login']);
        }

        throw e;
      }
    });
  }

  async logout() {
    // TODO: preloader
    // TODO: check status response
    // TODO: crear un logout page para confirmar que el usuario quiere salir?
    await this._logoutController.post();
    this._router.navigate(['/login']);
  }

  onSelectEntry(categoryId: string) {
    this.menu.visible = false;
    this._router.navigate(['/home', categoryId]);
  }

  toggleMenu() {
    this.menu.toggle();
  }
}
