import { BaseDocument } from '../../core/document/base-document';

export abstract class BaseController {
  constructor(public url: string) { }

  abstract async get(): Promise<BaseDocument>;
  abstract async post(document: BaseDocument): Promise<BaseDocument>;
}
