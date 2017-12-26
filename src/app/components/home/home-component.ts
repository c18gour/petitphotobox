import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';

import { SessionError } from '../../core/exception/session-error';

import { HomeController } from '../../controllers/home-controller';
import { LogoutController } from '../../controllers/logout-controller';
import { HomeEntity } from '../../entities/home-entity';

@Component({
  selector: 'app-home',
  templateUrl: './home-component.html',
  styleUrls: ['./home-component.scss']
})
export class HomeComponent implements OnInit {
  entity: HomeEntity;

  constructor(
    private _controller: HomeController,
    private _logoutController: LogoutController,
    private _router: Router
  ) { }

  async ngOnInit() {
    try {
      this.entity = await this._controller.get();
    } catch (e) {
      if (e instanceof SessionError) {
        this._router.navigate(['/login']);
      }

      throw e;
    }
  }

  async logout() {
    // TODO: preloader
    // TODO: check status response
    await this._logoutController.post();
    this._router.navigate(['/login']);
  }

  async onSelect(categoryId: string) {
    this.entity = await this._controller.get({ category_id: categoryId });
  }
}
