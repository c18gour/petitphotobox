/**
 * Why do we extend the Error class in this strange way?
 * Visit the following link for more info:
 *   https://stackoverflow.com/a/47941414/1704895
 */
export class ClientException extends Error {
  __proto__: Error;

  constructor(message: string) {
    const trueProto = new.target.prototype;
    super(message);
    this.__proto__ = trueProto;
  }
}
