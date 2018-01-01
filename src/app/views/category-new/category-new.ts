import { Component, OnInit, ViewChild } from '@angular/core';
import { Location } from '@angular/common';
import { Router, ActivatedRoute } from '@angular/router';

import {
  CategoryNewController
} from '../../controllers/category-new-controller';
import { CategoryNewEntity } from '../../entities/category-new-entity';
import {
  InputTreeComponent
} from '../../components/input-tree/input-tree-component';

@Component({
  selector: 'app-edit-category',
  templateUrl: './category-new.html',
  styleUrls: ['./category-new.scss']
})
export class CategoryNewView implements OnInit {
  entity: CategoryNewEntity;
  isRequesting = false;
  errorMessage = '';

  constructor(
    private _controller: CategoryNewController,
    private _router: Router,
    private _route: ActivatedRoute,
    private _location: Location
  ) { }

  @ViewChild('parentCategoryInput')
  parentCategoryInput: InputTreeComponent;

  async ngOnInit() {
    this._route.params.subscribe(async (params) => {
      const parentCategoryId = params.parentCategoryId;

      this.isRequesting = true;
      try {
        this.entity = await this._controller.get({ parentCategoryId });
      } catch (e) {
        this.errorMessage = e.message;
        throw e;
      } finally {
        this.isRequesting = false;
      }
    });
  }

  goBack() {
    this._location.back();
  }

  async onSubmit() {
    this.isRequesting = true;
    this.errorMessage = '';
    try {
      this.entity = await this._controller.post({
        parentCategoryId: this.parentCategoryInput.value,
        categoryId: '',
        title: this.entity.title
      });
    } catch (e) {
      this.errorMessage = e.message;
      throw e;
    } finally {
      this.isRequesting = false;
    }

    this._router.navigate([`/home/${this.entity.id}`]);
  }
}
