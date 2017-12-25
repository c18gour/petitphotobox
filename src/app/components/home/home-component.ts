import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';

import { HomeController } from '../../controllers/home-controller';
import { LogoutController } from '../../controllers/logout-controller';
import { HomeDocument } from '../../documents/home-document';

@Component({
  selector: 'app-home',
  templateUrl: './home-component.html',
  styleUrls: ['./home-component.scss']
})
export class HomeComponent implements OnInit {
  document: HomeDocument;

  constructor(
    private _controller: HomeController,
    private _logoutController: LogoutController,
    private _router: Router
  ) { }

  async ngOnInit() {
    this.document = await this._controller.get();
  }

  async logout() {
    // TODO: preloader
    // TODO: check status response
    await this._logoutController.post();
    this._router.navigate(['/login']);
  }
}
