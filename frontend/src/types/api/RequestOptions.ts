import config from "@/config";

export default class RequestOptions {
  private _stringify: boolean = true;
  private _ignoredByState: boolean = true;
  private _baseUrl: string = config.hosts.api;

  get stringify() {
    return this._stringify;
  }

  get ignoredByState() {
    return this._ignoredByState;
  }

  get baseUrl() {
    return this._baseUrl;
  }

  setStringify(value: boolean): this {
    this._stringify = value;
    return this;
  }

  setIgnoredByState(value: boolean): this {
    this._ignoredByState = value;
    return this;
  }

  setBaseUrl(value: string): this {
    this._baseUrl = value;
    return this;
  }
}
