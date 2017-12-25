import { BaseDocument } from '../../../core/model/document/base-document';

export abstract class BaseController {
  constructor(public url: string) { }
}
