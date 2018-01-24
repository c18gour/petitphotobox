export class Url {

  private constructor(
    public protocol: string,
    public username: string,
    public password: string,
    public hostname: string,
    public pathname: string,
    public params: { [key: string]: string | undefined },
    public hash: string
  ) { }

  static parse(url: string, base?: string) {
    const info = new URL(url, base);

    // parse params
    const params = {};
    const query = info.search
      .replace(/^\?/, '').split('&').filter((item) => item);
    for (const param of query) {
      const items = param.split('=');
      const name = decodeURIComponent(items[0]);
      const value = items.length > 1 ? decodeURIComponent(items[1]) : undefined;

      params[name] = value;
    }

    return new Url(
      info.protocol,
      info.username,
      info.password,
      info.hostname,
      info.pathname.replace(/^\//, ''),
      params,
      info.hash.replace(/^#/, '')
    );
  }

  addParam(name: string, value?: string) {
    this.params[name] = value;
  }

  removeParam(name: string) {
    delete this.params[name];
  }

  toString() {
    let str = `${this.protocol}//`;

    if (this.username || this.password) {
      str += `${this.username}`;

      if (this.password) {
        str += `:${this.password}`;
      }

      str += '@';
    }

    str += `${this.hostname}/${this.pathname}`;

    const params = [];
    for (const name in this.params) {
      if (this.params.hasOwnProperty(name)) {
        const value = this.params[name];

        let item = encodeURIComponent(name);
        if (value !== undefined) {
          item += `=${encodeURIComponent(value)}`;
        }

        params.push(item);
      }
    }

    const query = params.join('&');
    if (query) {
      str += `?${query}`;
    }

    if (this.hash) {
      str += `#${this.hash}`;
    }

    return str;
  }
}
