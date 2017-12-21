export abstract class BaseModel {
  /**
   * Creates an instance based on a JSON [document].
   */
  constructor(public model: any) { }

  get status() {
    return this.model.status;
  }

  get message() {
    return this.model.message;
  }
}
