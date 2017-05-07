+function ($) {
    "use strict";

    // Tile类函数定义
    var Tile = function (element, options) {
        this.$element = $(element);  // 容器元素       
        this.options = options;    // 插件运行参数，根据初始化代码，优先级最高的是所点击元素上的data-属性，然后是容器上的data-属性，最后才是默认值

        this.frames = this.$element.children(".tile-content");  //查找有多少个主区域需要滚动
        this.currentIndex = 0;  // 当前所显示主区域的索引
        this.interval = 0; // 触发设置
        this.size = { // 获取当前磁贴的高度和宽度
            'width': this.$element.width(),
            'height': this.$element.height()
        };

        // 确保默认的参数都是正常传值，如果是undefined，就使用默认值
        if (this.options.direction == undefined) { this.options.direction = Tile.DEFAULTS.direction; }
        if (this.options.period == undefined) { this.options.period = Tile.DEFAULTS.period; }
        if (this.options.duration == undefined) { this.options.duration = Tile.DEFAULTS.duration; }
        if (this.options.easing == undefined) { this.options.easing = Tile.DEFAULTS.easing; }

        // 定义一个默认的动画效果，可以使用jQuery的easing插件
        $.easing.doubleSqrt = function (t) { return Math.sqrt(Math.sqrt(t)); };
    }

    Tile.DEFAULTS = {
        direction: 'slideLeft',  // 默认滚动方向
        period: 3000,  // 默认暂停间隔
        duration: 700,  // 默认滚动时间
        easing: 'doubleSqrt'  // 默认动画效果
    }

    // 启动执行动画
    Tile.prototype.start = function () {
        var that = this;
        this.interval = setInterval(function () {
            that.animate();
        }, that.options.period);
    }

    // 暂停动画
    Tile.prototype.pause = function () {
        var that = this;
        clearInterval(that.interval);
    }

    // 动画处理入口，再分别调用各自方向的动画处理效果
    Tile.prototype.animate = function () {
        var that = this;
        var currentFrame = this.frames[this.currentIndex], nextFrame;
        this.currentIndex += 1;
        if (this.currentIndex >= this.frames.length) this.currentIndex = 0;
        nextFrame = this.frames[this.currentIndex];

        // 根据滚动方向，分别调用相应的内部方法，参数是：当前要滚动的tile-content，下一个要滚动的tile-content
        switch (this.options.direction) {
            case 'slideLeft': this.slideLeft(currentFrame, nextFrame); break;
            case 'slideRight': this.slideRight(currentFrame, nextFrame); break;
            case 'slideDown': this.slideDown(currentFrame, nextFrame); break;
            case 'slideUpDown': this.slideUpDown(currentFrame, nextFrame); break;
            case 'slideLeftRight': this.slideLeftRight(currentFrame, nextFrame); break;
            default: this.slideUp(currentFrame, nextFrame);
        }
    }

    // 左右来回滚动效果
    Tile.prototype.slideLeftRight = function (currentFrame, nextFrame) {
        if (this.currentIndex % 2 == 1)
            this.slideLeft(currentFrame, nextFrame);
        else
            this.slideRight(currentFrame, nextFrame);
    }

    // 上下来回滚动效果
    Tile.prototype.slideUpDown = function (currentFrame, nextFrame) {
        if (this.currentIndex % 2 == 1)
            this.slideUp(currentFrame, nextFrame);
        else
            this.slideDown(currentFrame, nextFrame);
    }

    // 一直向上滚动效果
    Tile.prototype.slideUp = function (currentFrame, nextFrame) {
        var move = this.size.height;
        var options = {
            'duration': this.options.duration,
            'easing': this.options.easing
        };

        $(currentFrame).animate({ top: -move }, options);
        $(nextFrame)
            .css({ top: move })
            .show()
            .animate({ top: 0 }, options);
    }

    // 一直向下滚动效果
    Tile.prototype.slideDown = function (currentFrame, nextFrame) {
        var move = this.size.height;
        var options = {
            'duration': this.options.duration,
            'easing': this.options.easing
        };

        $(currentFrame).animate({ top: move }, options);
        $(nextFrame)
            .css({ top: -move })
            .show()
            .animate({ top: 0 }, options);
    }

    // 一直向左滚动效果
    Tile.prototype.slideLeft = function (currentFrame, nextFrame) {
        var move = this.size.width;
        var options = {
            'duration': this.options.duration,
            'easing': this.options.easing
        };

        $(currentFrame).animate({ left: -move }, options);
        $(nextFrame)
            .css({ left: move })
            .show()
            .animate({ left: 0 }, options);
    }

    // 一直向右滚动效果
    Tile.prototype.slideRight = function (currentFrame, nextFrame) {
        var move = this.size.width;
        var options = {
            'duration': this.options.duration,
            'easing': this.options.easing
        };

        $(currentFrame).animate({ left: move }, options);
        $(nextFrame)
            .css({ left: -move })
            .show()
            .animate({ left: 0 }, options);
    }


    // Tile插件定义

    var old = $.fn.tile;
    // 保留其它库的$.fn.tile代码（如果定义的话，以便在noConflict之后，可以继续使用该老代码

    $.fn.tile = function (option) {
        return this.each(function () { //遍历所有符合规则的元素
            var $this = $(this)   //当前触发元素的jQuery对象
            var data = $this.data('bs.tile') // 获取自定义属性data-bs.tile的值（其实是tile实例）
            var options = $.extend({}, Tile.DEFAULTS, $this.data(), typeof option == 'object' && option) // 合并参数，优先级依次递增

            if (!data) $this.data('bs.tile', (data = new Tile(this, options)))   // 如果没有tile实例，就初始化一个，并传入this和参数

            option === 'pause' ? data.pause() : data.start();
        })
    }

    $.fn.tile.Constructor = Tile; // 并重设插件构造器,可以通过该属性获取插件的真实类函数

    // Tile 防冲突
    $.fn.tile.noConflict = function () {
        $.fn.tile = old
        return this
    }

    // Tile DATA-API
    // 由于不需要click相关的时间，所以这里只是简单触发tile插件

    $(window).on('load', function () {
        $('[data-toggle="tile"]').each(function () { //遍历所有符合规则的元素
            $(this).tile();  // 实例化插件以便自动运行
        })
    })

}(jQuery);
