export abstract class BaseDocument {
  /**
   * Creates an instance based on a JSON [document].
   */
  constructor(public document: any) { }

  get status() {
    return this.document.status;
  }

  get message() {
    return this.document.message;
  }
}
