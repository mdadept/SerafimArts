var Renderer = PIXI.WebGLRenderer;
var Blur = PIXI.filters.BlurFilter;
var Container = PIXI.Container;
var Sprite = PIXI.Sprite;
var Texture = PIXI.Texture;

/**
 * Class Parallax
 */
export default class Parallax {
    highQuality = 5;
    lowQuality = 1;

    /**
     * @type {void|KnockoutObservable<T>}
     */
    quality = ko.observable(this.lowQuality);

    /**
     * @type {Renderer}
     */
    renderer = PIXI.autoDetectRenderer(this.width, 800);

    /**
     * @type {Container}
     */
    stage = new Container;

    /**
     * @type {number}
     * @private
     */
    _scrollY = 0;

    /**
     * @type {PIXI.Container}
     */
    smoke = new PIXI.Container();

    /**
     * @type {number}
     */
    width = 1100;

    /**
     * @type {number}
     */
    mill = 0;

    /**
     * @type {number}
     */
    lowQualitySmoke = 15;

    /**
     * @type {number}
     */
    lastTime = 0;

    /**
     * @param context
     */
    constructor(context:HTMLElement) {
        PIXI.utils._saidHello = true;
        this.renderer.backgroundColor = 0xab998d;

        window.addEventListener('scroll', event => {
            this._scrollY = window.pageYOffset;
            if (this._scrollY > this.renderer.height) {
                this._scrollY = this.renderer.height;
            }
        }, true);

        context.appendChild(this.renderer.view);

        this._load(this.stage);

        this.quality.subscribe(value => {
            var i = 0, len = 0;
            for (i = 0, len = this.stage.children.length; i < len; i++) {
                var sprite = this.stage.getChildAt(i);

                if (sprite.blurFilter) {
                    sprite.blurFilter.passes = value;
                }
            }

            for (i = 0, len = this.smoke.children.length; i < len; i++) {
                this.smoke.getChildAt(i).visible = (value === this.highQuality || i < this.lowQualitySmoke);
            }
        });

        var smoke1 = Texture.fromImage('/img/header/parallax/smoke/smoke-1.png');
        var smoke2 = Texture.fromImage('/img/header/parallax/smoke/smoke-2.png');

        for (var i = 0; i < 20; i++) {
            var sprite = new Sprite(Math.random() > 0.5 ? smoke1 : smoke2);
            sprite.id = i;
            sprite.x = Math.random() * screen.width - 128;
            sprite.y = Math.random() * 400 - 150;
            sprite.visible = i < this.lowQualitySmoke;
            sprite.movementSpeed = (Math.random() + .2);

            this.smoke.addChild(sprite);
        }

        this.smoke.alpha = 1;
        this.smoke.depth = .6;
        this.smoke.shift = {x: 0, y: 0};

        this.lastTime = (new Date()).getMilliseconds();

        this._render();
    }

    /**
     * @returns {Parallax}
     */
    setLowQuality() {
        this.quality(this.lowQuality);
        return this;
    }

    /**
     * @returns {Parallax}
     */
    setHighQuality() {
        this.quality(this.highQuality);
        return this;
    }

    /**
     * @private
     */
    _render() {
        var delta = (new Date()).getMilliseconds() - this.lastTime;
        if (delta < 0) { delta = 0; }

        var i = 0, len = 0;


        this.renderer.resize(
            document.body.getBoundingClientRect().width,
            this.renderer.height
        );

        for (i = 0, len = this.stage.children.length; i < len; i++) {
            this._updatePosition(delta, this.stage.getChildAt(i));
        }

        this.renderer.render(this.stage);

        this.lastTime = (new Date()).getMilliseconds();
        requestAnimationFrame(::this._render);
    }

    /**
     * @param deltaTime
     * @param sprite
     * @private
     */
    _updatePosition(deltaTime, sprite:Sprite) {
        sprite.y = sprite.shift.y + this._scrollY * (sprite.depth - 1) * -1;

        if (sprite.blurFilter) {
            if (sprite.centrize) {
                sprite.x = (this.renderer.width - sprite.width) / 2 + sprite.shift.x;
            }
            var delta = Math.abs(this._scrollY * (1 - sprite.depth) / 40);

            if (this.quality() > this.lowQuality && this._scrollY < this.renderer.height) {
                sprite.blurFilter.blur = delta + sprite.depth * 3;
            } else if (this.quality() === this.lowQuality) {
                sprite.blurFilter.blur = 0;
            }

            if (sprite.rotatable) {
                sprite.anchor.set(.5, .5);
                sprite.rotation += (.005 * deltaTime / 30);
            }
        }

        if (sprite instanceof Container) {
            for (var i = 0, len = sprite.children.length; i < len; i++) {
                var smoke = sprite.getChildAt(i);
                smoke.x += (smoke.movementSpeed * deltaTime / 30);

                if (smoke.movementSpeed > 0 && smoke.x > this.renderer.width) {
                    smoke.x = -smoke.width;
                } /*else if (smoke.movementSpeed < 0 && smoke.x < -smoke.width) {
                    smoke.x = this.renderer.width;
                }*/
            }
        }
    }

    /**
     * @private
     */
    _load(container:Container) {
        for (var i = 0, len = this.layers.length; i < len; i++) {
            var data = this.layers[i];

            data.item.shift = {x: data.x, y: data.y};
            data.item.depth = data.depth;

            data.item.blurFilter = new Blur;
            data.item.blurFilter.passes = this.quality();
            data.item.filters = [data.item.blurFilter];
            data.item.centrize = !!data.centrize;
            data.item.rotatable = !!data.rotate;

            if (i === 5) {
                this.stage.addChild(this.smoke);
            }

            container.addChild(data.item);

            this._updatePosition(0, data.item);
        }
    }

    get layers() {
        return [
            {
                centrize: true,
                item: new Sprite(Texture.fromImage('/img/header/parallax/bg.jpg')),
                depth: 0,
                x: 0,
                y: 0
            },
            {
                centrize: true,
                item: new Sprite(Texture.fromImage('/img/header/parallax/1.png')),
                depth: .2,
                x: 0,
                y: 86
            },
            {
                rotate: true,
                centrize: true,
                item: new Sprite(Texture.fromImage('/img/header/parallax/mill.png')),
                depth: .3,
                x: 100,
                y: 330
            },
            {
                centrize: true,
                item: new Sprite(Texture.fromImage('/img/header/parallax/2.png')),
                depth: .3,
                x: 0,
                y: 138
            },
            {
                centrize: true,
                item: new Sprite(Texture.fromImage('/img/header/parallax/3.png')),
                depth: .4,
                x: 0,
                y: 254
            },
            {
                centrize: true,
                item: new Sprite(Texture.fromImage('/img/header/parallax/4.png')),
                depth: .6,
                x: 0,
                y: 503
            },
            {
                centrize: true,
                item: new Sprite(Texture.fromImage('/img/header/parallax/5.png')),
                depth: .8,
                x: 0,
                y: 358
            },
            {
                centrize: true,
                item: new Sprite(Texture.fromImage('/img/header/parallax/6.png')),
                depth: 1.1,
                x: 0,
                y: 500
            },
            {
                centrize: false,
                item: new Sprite(Texture.fromImage('/img/header/parallax/sun.png')),
                depth: .1,
                x: 0,
                y: 0
            },
            {
                centrize: true,
                item: new Sprite(Texture.fromImage('/img/header/parallax/overlay.png')),
                depth: 0,
                x: 0,
                y: -140 // -280
            }
        ];
    }
}