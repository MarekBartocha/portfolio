class Compsoul {
  get version() {
  	return 0.5;
  }

  constructor(selector) {
    let _selector;
    switch (typeof selector) {
      case "string":
        _selector = document.querySelectorAll(selector);
        break;
      case "object":
        _selector = (selector === null) ? {} : (typeof selector.length === "undefined") ? [ selector ] : selector;
        break;
      case "boolean":
        _selector = {};
        break;
      case "undefined":
        _selector = {};
        break;
      default:
        throw new TypeError(this.constructor.name + " - selector error: " + selector);
    }

    this.length = _selector.length;
    Object.assign(this, _selector);

  }

  addClass(classList) {
    let len = this.length;

    while(len--) {
      this[len].classList.add(...classList.split(" "));
    }

    return this;
  }

  ajax(settings) {
    let request = new XMLHttpRequest(),
        type = settings.type,
        url = settings.url,
        data = settings.data,
        success = (settings.success) ? settings.success : () => {},
        error = (settings.error) ? settings.error : () => {};

    if (type === "GET") {
      if(!url) throw new TypeError("url error");

      request.open(type, url, true);
      request.onload = () => {
        if (request.status >= 200 && request.status < 400) {
          success(request.responseText);
        } else {
          console.warn("request error");
          error();
        }
      };

      request.onerror = () => {
        error();
      };

      request.send();
    } else if (type === "POST") {
      if(!url) throw new TypeError("url error");
      if(!data) throw new TypeError("data error");

      request.open(type, url, true);
      request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
      request.send(data);
    }

    return this;
  }

  append(element) {
    let len = this.length;

    while(len--) {
      this[len].appendChild(element);
    }

    return this;
  }

  attr(attribute, value) {
    let len = this.length;

    if(typeof value !== "undefined") {
      while(len--) {
        this[len].setAttribute(attribute, value);
      }
    } else {
      return (this[0]) ? this[0].getAttribute(attribute) : false;
    }

    return this;
  }

  each(callback) {
    for(let i = 0; i < this.length; i++) {
      callback.apply(this[i]);
    }

    return this;
  }

  hasClass(className) {
    return this[0].classList.contains(className);
  }

  html(html) {
    let len = this.length;

    if(typeof html !== "undefined") {
      while(len--) {
        this[len].innerHTML = html;
      }
    } else {
      return this[0].innerHTML;
    }

    return this;
  }

  index() {
    let parent = (this[0]) ? this[0].parentElement : false,
        len = (this[0]) ? parent.children.length : false,
        index = -1;

    while(len--) {
      if (parent.children[len] === this[0]) index = len;
    }

    return index;
  }

  on(type, callback) {
    let len = this.length;

    while(len--) {
      this[len].addEventListener(type, callback);
    }

    return this;
  }

  off(eventName, eventHandler) {
    let len = this.length;

    while(len--) {
      this[len].removeEventListener(eventName, eventHandler);
    }

    return this;
  }

  remove() {
    let len = this.length;

    while(len--) {
      if(this[len].parentNode) this[len].parentNode.removeChild(this[len]);
    }

    return this;
  }

  removeAttr(attribute) {
    let len = this.length;

    while(len--) {
      this[len].removeAttribute(attribute);
    }

    return this;
  }

  removeClass(classList) {
    let len = this.length;

    while(len--) {
      this[len].classList.remove(...classList.split(" "));
    }

    return this;
  }

  toggleClass(classList) {
    let len = this.length;

    while(len--) {
      this[len].classList.toggle(...classList.split(" "));
    }

    return this;
  }

}
