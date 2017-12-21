import { BaseModel } from '../../core/model/base-model';

export abstract class BaseController {
  constructor(public url: string) { }

  abstract async get(): Promise<BaseModel>;
  abstract async post(document: BaseModel): Promise<BaseModel>;
}
