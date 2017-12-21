import { BaseDocument } from '../../core/model/document/base-document';

export abstract class BaseController {
  constructor(public url: string) { }

  abstract async get(): Promise<BaseDocument>;
  abstract async post(...params: any[]): Promise<BaseDocument>;
}
