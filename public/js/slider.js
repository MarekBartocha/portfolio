class Slider {
  constructor(selector) {
    window.Compsoul = window.Compsoul || window.jQuery;
    this.version = 3.1;
    this.settings = {
      classActive: "compsoul-active",
      classFirst: "compsoul-first",
      classNext: "compsoul-next",
      classPrev: "compsoul-prev",
      classPrevious: "compsoul-previous",
      classLoaded: "compsoul-loaded",
      classLoading: "compsoul-loading",
      classError: "compsoul-error",
      classUnset: "compsoul-unset",
      classAnimation: "compsoul-animation",
      classWait: "compsoul-wait",
      classDirectionUp: "compsoul-direction-up",
      classDirectionDown: "compsoul-direction-down",

      selector: selector,

      next: ".compsoul-slide-next",
      prev: ".compsoul-slide-prev",
      nav: ".compsoul-slide-nav",
      timeline: ".compsoul-slide-timeline",
      parent: false,
      height: false,

      cover: true,
      sliderCover: "figure",
      sliderImg: "figure img",

      animation: true,
      animationend: "false",

      load: true,
      preload: true,
      first: 0,
      loop: true,

      responsive: {}
    }

    this.library = class Library {
      constructor($node, slider) {
        this.$node = $node;
        this.element = this.$node[0];
        this.slider = slider;
        this.settings = slider.settings;
      }

      $(selector) {
        return new Compsoul(selector);
      }

      active() {
        this.$node.addClass(this.settings.classActive);
        return this;
      }

      directionUp() {
        this.$node.addClass(this.settings.classDirectionUp);
        return this;
      }

      directionDown() {
        this.$node.addClass(this.settings.classDirectionDown);
        return this;
      }

      error() {
        this.$node.removeClass(this.settings.classLoading).addClass(this.settings.classError);
        return this;
      }

      first() {
        this.$node.addClass(this.settings.classFirst);
        return this;
      }

      inactive() {
        this.$node.removeClass(this.settings.classActive);
        return this;
      }

      unload() {
        this.$node.removeClass(this.settings.classLoaded);
        return this;
      }

      animation() {
        this.$node.removeClass(this.settings.classUnset).addClass(this.settings.classAnimation);
        return this;
      }

      unset() {
        this.$node.removeClass(this.settings.classAnimation).addClass(this.settings.classUnset);
        return this;
      }

      loaded() {
        this.$node.removeClass(this.settings.classLoading).addClass(this.settings.classLoaded);
        return this;
      }

      loading() {
        this.$node.removeClass(this.settings.classLoaded).addClass(this.settings.classLoading);
        return this;
      }

      next() {
        this.$node.addClass(this.settings.classNext);
        return this;
      }

      prev() {
        this.$node.addClass(this.settings.classPrev);
        return this;
      }

      previous() {
        this.$node.addClass(this.settings.classPrevious);
        return this;
      }

      reset() {
        this.$node.removeClass(this.settings.classActive + " " + this.settings.classFirst + " " + this.settings.classNext + " " + this.settings.classPrev + " " + this.settings.classPrevious + " " + this.settings.classWait + " " + this.settings.classDirectionUp + " " + this.settings.classDirectionDown);
        return this;
      }

      restart() {
        void this.element.offsetWidth;
        return this;
      }

      on() {
        this.$node.on("animationend", this.slider.unlock).on("transitionend", this.slider.unlock);
      }

      off() {
        this.$node.off("animationend", this.slider.unlock).off("transitionend", this.slider.unlock);
      }

      condition(condition, callback) {
        if(condition) callback.apply(this);
        return this;
      }

      wait() {
        this.$node.addClass(this.settings.classWait);
        this.slider.lock = true;
        return this;
      }

      done() {
        this.$node.removeClass(this.settings.classWait);
        this.slider.lock = false;
        this.slider.slider.off();
        return this;
      }

      responsive(element) {
        if(!element) return;
        for (let key in element.dataset) {
          if (window.innerWidth <= parseInt(key)) {
            return element.dataset[key];
          }
        }
      }

      change(img, src) {
        return (img) ? (!(img.src && src && (img.src.replace(img.src.replace(src, ""), "") === src || src.replace(src.replace(img.src, ""), "") === img.src))) : false;
      }

      cover(element) {
        let parent = element.querySelector(this.settings.sliderCover),
            child = element.querySelector(this.settings.sliderImg);

        if(parent && child) parent.style.backgroundImage = "url(" + (this.responsive(child) || child.dataset.src) + ")";
        return this;
      }

      background(element, url) {
        let background = (element) ? /(?:\(['"]?)(.*?)(?:['"]?\))/.exec(element.style.backgroundImage) : false;
        return (background && (background[1] = url) ? true : false);
      }

      src(element) {
        let img = element.querySelector(this.settings.sliderImg);
        if(img) img.src = this.responsive(img) || img.dataset.src;
        return this;
      }

      load(callback, type, index, debug) {
        let figure = this.element.querySelector(this.settings.sliderCover),
            img = this.element.querySelector(this.settings.sliderImg),
            src = (img) ? this.responsive(img) || img.dataset.src : false,
            change = this.change(img, src);

        if(change) img.src = src;
        if((this.$node.hasClass(this.settings.classLoaded) || !img || (img.complete && img.src && !change)) && this.background(figure, src)) {
          if(callback && ((type === "active" && this.slider.active.element === this.element) || (type === "next" && this.slider.next.element === this.element) || (type === "prev" && this.slider.prev.element === this.element) || type === "preload")) callback();
          this.loaded();
          this.slider.point(index, "loaded");
          return;
        }

        if(img) {
          img.compsoulStack = img.compsoulStack || [];
          this.loading();
          this.slider.point(index, "loading");
          if(img.compsoulStack.length === 0) {
            img.src = src;
            img.compsoulStack.push({
              object: this,
              img: img,
              callback: callback,
              type: type,
              src: src,
              index: index,
              debug: debug
            });
            this.onload = this.slider.onload.bind(this, img);
            this.onerror = this.slider.onerror.bind(this, img);

            img.addEventListener("load", this.onload);
            img.addEventListener("error", this.onerror);
          } else {
            img.compsoulStack.push({
              object: this,
              img: img,
              callback: callback,
              type: type,
              src: src,
              index: index,
              debug: debug
            });
          }
        }
      }
    }
  }

  $(selector) {
    return new Compsoul(selector);
  }

  factory($element) {
    return new this.library($element, this);
  }

  root() {
    this.html = this.factory(this.$("html"));
    this.head = this.factory(this.$("head"));
    this.body = this.factory(this.$("body"));
  }

  set(index) {
    this.modernize(index);
    this.restart();
    this.navigation();
    this.slider.reset().off();
    if(this.timeline && this.timeline.element) (this.settings.load) ? this.timeline.unset().restart().animation().inactive() : this.timeline.unset().restart().animation().active();

    this.past = (this.index.past !== false) ? this.factory(this.$(this.$slider[this.index.past])).previous().condition(this.settings.animation && this.index.past !== index, function() {this.wait()}) : false;

    this.active = this.factory(this.$(this.$slider[this.index.value])).condition(!this.launch, function() {this.first()}).condition(this.index.direction === 0, function() {this.directionUp()}).condition(this.index.direction === 1, function() {this.directionDown()}).active();
    this.active.condition(this.settings.load, function() {this.load(() => {if(this.slider.timeline) this.slider.timeline.active()}, "active", this.slider.index.value, "set-active")}).condition(this.settings.animation, () => {this.active.on()});

    this.next = this.factory(this.$(this.$slider[this.index.next]));
    this.next.condition(this.settings.load, function() {this.load(() => {if(this.slider.settings.next) this.slider.factory(this.$(this.slider.settings.next)).loaded()}, "next", this.slider.index.next, "set-next")}).next();

    this.prev = this.factory(this.$(this.$slider[this.index.prev]));
    this.prev.condition(this.settings.load, function() {this.load(() => {if(this.slider.settings.prev) this.slider.factory(this.$(this.slider.settings.prev)).loaded()}, "prev", this.slider.index.prev, "set-prev")}).prev();

    this.height();
  };

  modernize(index) {
    this.launch = (!this.index) ? false : true;
    this.index = this.index || {};
    this.index.direction = (this.index.value > index) ? 1 : 0;
    this.index.past = (this.launch) ? this.index.value : false;
    this.index.value = (this.settings.loop) ? (index >= this.len) ? 0 : (index < 0) ? this.len - 1 : index : (index >= this.len) ? this.len - 1 : (index < 0) ? 0 : index;
    this.index.next = (this.index.value + 1 >= this.len) ? 0 : this.index.value + 1;
    this.index.prev = (this.index.value - 1 < 0) ? this.len - 1 : this.index.value - 1;
  }

  load(index) {
    let len = this.len;

    while (len--) {

    }
    if(this.settings.preload) this.preload(index, 1);
    if(this.settings.preload) this.preload(index - 1, -1);
  }

  preload(index, direction) {
    if(index >= 0 && index < this.len) {
      this.factory(this.$(this.$slider[index])).load(() => {
        this.preload(index + direction, direction);
      }, "preload", index, "preload");
    }
  }

  onload(img) {
    let array = img.compsoulStack;
    img.compsoulStack = [];
    img.removeEventListener("load", this.onload);
    img.removeEventListener("error", this.onerror);

    array.forEach((item) => {
      if(!this.change(img, item.src)) {
        if(item.callback && ((item.type === "active" && this.slider.active.element === this.element) || (item.type === "next" && this.slider.next.element === this.element) || (item.type === "prev" && this.slider.prev.element === this.element) || item.type === "preload")) item.callback();
        if(this.settings.cover) this.cover(this.element);
        this.loaded();
        this.slider.point(item.index, "loaded");
      }
    });
  }

  onerror(img) {
    let array = img.compsoulStack;
    img.compsoulStack = [];
    img.removeEventListener("load", this.onload);
    img.removeEventListener("error", this.onerror);

    array.forEach((item) => {
      if(!this.change(img, item.src)) {
        if(item.callback && ((item.type === "active" && this.slider.active.element === this.element) || (item.type === "next" && this.slider.next.element === this.element) || (item.type === "prev" && this.slider.prev.element === this.element) || item.type === "preload")) item.callback();
        this.error();
        this.slider.point(item.index, "error");
      }
    });
  }

  restart() {
    if(this.settings.next) this.factory(this.$(this.settings.next)).inactive();
    if(this.settings.prev) this.factory(this.$(this.settings.prev)).inactive();
    if(this.settings.nav) this.factory(this.$(this.settings.nav)).inactive();
  }

  reload() {
    for (let index = 0; index < this.len; index++) {
      let element = this.factory(this.$(this.$slider[index])),
          img = element.element.querySelector("img"),
          src = (img) ? element.responsive(img) || img.dataset.src : false,
          change = element.change(img, src);

      if(img && !(img.complete && img.src && !change)) {
        element.unload();
        if(this.settings.nav) this.factory(this.$(this.$(this.settings.nav)[index])).unload();
      }
    }
    if(this.settings.next) this.factory(this.$(this.settings.next)).unload();
    if(this.settings.prev) this.factory(this.$(this.settings.prev)).unload();
  }

  navigation() {
    if(this.settings.next) this.factory(this.$(this.settings.next)).condition(this.settings.load, function() {this.loading()}).condition(this.settings.loop || (!this.settings.loop && this.index.value !== this.len - 1), function() {this.active()});
    if(this.settings.prev) this.factory(this.$(this.settings.prev)).condition(this.settings.load, function() {this.loading()}).condition(this.settings.loop || (!this.settings.loop && this.index.value !== 0), function() {this.active()});
    this.point(this.index.value, "active");
  }

  point(index, type) {
    if(this.settings.nav) {
      let that = this;
      this.$(this.settings.nav).each(function() {
        if(this.parentNode.children[index]) that.factory(that.$(this.parentNode.children[index])).condition(type === "active", function() {this.active()}).condition(type === "loaded", function() {this.loaded()}).condition(type === "loading", function() {this.loading()}).condition(type === "error", function() {this.error()});
      });
    }
  }

  height() {
    if(this.settings.height) this.parent.element.style.minHeight = this.factory(this.$(this.$slider[this.index.value])).element.offsetHeight + "px";
  }

  event() {
    this.click = (event) => {
      let that = this;
      if(this.settings.next && !this.lock) {
        this.$(this.settings.next).each(function() {
          if(((!that.settings.load && event.target === this) || (that.settings.load && event.target === this && that.$(event.target).hasClass(that.settings.classLoaded))) && that.$(event.target).hasClass(that.settings.classActive)) {
            that.set((that.index.value) ? that.index.value + 1 : that.first + 1);
            return;
          }
        });
      }

      if(this.settings.prev && !this.lock) {
        this.$(this.settings.prev).each(function() {
          if(((!that.settings.load && event.target === this) || (that.settings.load && event.target === this && that.$(event.target).hasClass(that.settings.classLoaded))) && that.$(event.target).hasClass(that.settings.classActive)) {
            that.set((that.index.value) ? that.index.value - 1 : that.first - 1);
            return;
          }
        });
      }

      if(this.settings.nav && !this.lock) {
        this.$(this.settings.nav).each(function() {
          if((!that.settings.load && event.target === this) || (that.settings.load && event.target === this && that.$(event.target).hasClass(that.settings.classLoaded))) {
            if(that.$(this).index() >= 0) that.set(that.$(this).index());
            return;
          }
        });
      }
    }
    this.html.$node.on("click", this.click);

    if(this.timeline) {
      this.animationend = () => {
        if(this.settings.loop || (!this.settings.loop && this.index.value !== this.len - 1)) (this.settings.load) ? this.factory(this.$(this.$slider[this.index.next])).load(() => {this.set(this.index.next)}, "next", this.index.next, "timeline") : this.set(this.index.value + 1);
      };
      this.timeline.$node.on("animationend", this.animationend);
    }

    this.ontouchstart = (event) => {
      this.touchstart = event.touches[0].screenX;
    };
    this.slider.$node.on("touchstart", this.ontouchstart);

    this.ontouchend = (event) => {
      if(!this.lock && (this.touchstart - event.changedTouches[0].screenX < -200) && ((!this.settings.loop && this.index.value !== 0) || this.settings.loop) && (!this.settings.load || (this.settings.load && this.prev.$node.hasClass(this.settings.classLoaded)))) this.set(this.index.value - 1);
      if(!this.lock && (this.touchstart - event.changedTouches[0].screenX > 200) && ((!this.settings.loop && this.index.value !== this.len - 1) || this.settings.loop) && (!this.settings.load || (this.settings.load && this.next.$node.hasClass(this.settings.classLoaded)))) this.set(this.index.value + 1);
    };
    this.slider.$node.on("touchend", this.ontouchend);

    this.unlock = (event) => {
      if((event.target === this.active.element || event.target === this.active.element.querySelector(this.settings.animationend)) && this.past) this.past.done();
    }

  };

  responsive() {
    let range;
    for (let key in this.settings.responsive) {
      if (window.innerWidth <= parseInt(key)) {
        this.options(this.default);
        this.update(this.settings.responsive[key]);
        range = true;
        return;
      }
    }
    if(!range) this.update(this.default);
  }

  remove() {
    if(this.html && this.click) this.html.$node.off("click", this.click);
    if(this.timeline) this.timeline.$node.off("animationend", this.animationend);
    if(this.ontouchstart) this.slider.$node.off("touchstart", this.ontouchstart);
    if(this.ontouchend) this.slider.$node.off("touchend", this.ontouchend);
  }

  update(settings) {
    if(settings) this.options(settings);
    if(!this.factory(this.$(this.settings.selector)).element) return;
    this.remove();
    this.boot();
    this.root();
    this.event();
    this.reload();
    this.set((this.index) ? this.index.value : this.first);
    this.load((this.index) ? this.index.value : this.first);
    this.slider.done();
  }

  rwd() {
    this.resize = compsoul.debounce(() => {
      this.responsive();
    }, 200);

    window.addEventListener("resize", this.resize);
  }

  compsoul() {
    window.compsoul = window.compsoul || {};

    compsoul.throttle = compsoul.throttle || ((callback, delay) => {
      let throttle;
      return (...args) => {
        if (!throttle) {
          callback(...args);
          throttle = setTimeout(() => throttle = false, delay)
        }
      };
    });

    compsoul.debounce = compsoul.debounce || ((callback, delay) => {
      let timeout;
      return (...args) => {
        const that = this;
        clearTimeout(timeout);
        timeout = setTimeout(() => callback.apply(that, args), delay);
      };
    });
  }

  boot(query) {
    this.$slider = new Compsoul(query || this.settings.selector);
    this.slider = this.factory(this.$slider);
    this.len = this.$slider.length;

    this.$parent = new Compsoul(this.settings.parent || ((this.slider.element) ? this.slider.element.parentNode : false));
    this.parent = this.factory(this.$parent);

    this.first = (this.settings.first === "random") ? Math.floor(Math.random() * this.len) : this.settings.first;
    this.timeline = (this.settings.timeline && this.len > 1) ? this.factory(this.$(this.settings.timeline)) : false;
  }

  options(settings) {
    if(!this.default) this.default = Object.assign({}, Object.assign(this.settings, settings));
    Object.assign(this.settings, settings);
    return this;
  }

  init() {
    this.compsoul();
    this.rwd();
    this.responsive();
    return this;
  }

}
