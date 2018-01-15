import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'iterator'
})
export class IteratorPipe implements PipeTransform {
  transform(numItems: number) {
    const ret = new Array<number>();

    for (let i = 0; i < numItems; i++) {
      ret.push(i);
    }

    return ret;
  }
}
