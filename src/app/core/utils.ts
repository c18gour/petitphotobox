import { environment as env } from '../../environments/environment';

export function fullPath(path: string) {
  return [env.apiUrl, path].join('/');
}
