export abstract class BaseDocument {

  constructor(private _document: any) { }

  get statusCode(): number {
    return this._document.status.code;
  }

  get statusMessage(): string {
    return this._document.status.message;
  }

  protected get body(): any {
    return this._document.body;
  }
}
