import { BaseDocument } from '../core/model/document/base-document';

export class HomeDocument extends BaseDocument {
  get categories() {
    return this.body.categories;
  }

  get pictures() {
    return this.body.pictures;
  }
}
