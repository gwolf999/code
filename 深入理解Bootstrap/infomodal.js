+function ($) {
    "use strict";

    // InfoModal类定义
    var InfoModal = function (element, options) {
        this.$element = $(element);
        this.super = this.$element.data('bs.modal'); // 获取自定义属性bs.modal的值;
        this.options = options;

        this.$element.on('click.confirm.infomodal', '[data-info="confirm"]', $.proxy(this.confirm, this));
        this.$element.on('click.cancel.infomodal', '[data-info="cancel"]', $.proxy(this.cancel, this));

        var that = this;// 防止污染作用域，用临时变量that

        this.$element.on("show.bs.modal", function (e) {
            that.$element.trigger(e = $.Event('show.bs.infomodal'));
            if (e.isDefaultPrevented()) return;
        });

        this.$element.on("shown.bs.modal", function (e) {
            that.$element.trigger(e = $.Event('shown.bs.infomodal'));
            if (e.isDefaultPrevented()) return;
        });

        this.$element.on("hide.bs.modal", function (e) {
            that.$element.trigger(e = $.Event('hide.bs.infomodal'));
            if (e.isDefaultPrevented()) return;
        });

        this.$element.on("hidden.bs.modal", function (e) {
            that.$element.trigger(e = $.Event('hidden.bs.infomodal'));
            if (e.isDefaultPrevented()) return;
        });

    }

    //默认设置
    InfoModal.DEFAULTS = {
        closeAfterConfirm: false
        , closeAfterCancel: true
    }

    // 反转弹窗状态
    InfoModal.prototype.toggle = function (_relatedTarget) {
        return this[!this.super.isShown ? 'show' : 'hide'](_relatedTarget)// 如果是关闭状态，则打开弹窗，否则就关闭
    }

    // 打开弹窗
    InfoModal.prototype.show = function (_relatedTarget) {
        this.super.show(_relatedTarget);
    }

    // 关闭弹窗
    InfoModal.prototype.hide = function (e) {
        if (e) e.preventDefault();// 先阻止冒泡行为
        this.super.hide();
    }

    // 点击确认按钮的行为
    InfoModal.prototype.confirm = function (e) {
        if (e) e.preventDefault();// 先阻止冒泡行为

        var e = $.Event('confirm.bs.infomodal');
        this.$element.trigger(e);// 确认前，触发事件，主要用于处理相关代码

        if (e.isDefaultPrevented()) return;

        if (this.options.closeAfterConfirm) {
            this.hide();// 如果设置了data-close-after-confirm=true参数，则关闭弹窗
        }
    }

    // 点击取消按钮的行为
    InfoModal.prototype.cancel = function (e) {
        if (e) e.preventDefault();// 先阻止冒泡行为

        var e = $.Event('cancel.bs.infomodal');
        this.$element.trigger(e);// 取消前，先触发事件

        if (e.isDefaultPrevented()) return;

        if (this.options.closeAfterCancel) {
            this.hide(e); // 如果设置了data-close-after-cancel=true参数，则关闭弹窗
        }
    }

    // InfoModal 插件定义
    var old = $.fn.infomodal
    // 保留其它库的$.fn.infomodal代码（如果定义的话，以便在noConflict之后，可以继续使用该老代码

    $.fn.infomodal = function (option, _relatedTarget) {
        return this.each(function () {
            // 根据选择器，遍历所有符合规则的元素

            var $this = $(this)
            var data = $this.data('bs.infomodal') // 获取自定义属性bs.modal的值
            var options = $.extend({}, InfoModal.DEFAULTS, $this.data(), typeof option == 'object' && option)
            // 将默认参数、选择器所在元素上的自定义属性（data-开头）、和option参数，这三种的值合并再一起，作为options参数
            // 优先级：后面的参数优先级高于前面的参数
            options.show = false; // 默认先关闭，然后在后面手工打开
            var modal = $this.modal(options, _relatedTarget);

            if (!data) $this.data('bs.infomodal', (data = new InfoModal(this, options)))
            // 如果值不存在，则将InfoModal实例设置为bs.infomodal的值          

            if (typeof option == 'string') {
                data[option](_relatedTarget)
            }
            else {
                data.show(_relatedTarget);
            }
            // 如果option传递了string，则表示要执行某个方法
            // 比如传入了show，则要执行InfoModal实例的show方法，data["show"]相当于data.show();
        })
    }

    $.fn.infomodal.Constructor = InfoModal; // 重设插件构造器,可以通过该属性获取插件的真实类函数

    // InfoModal防冲突
    $.fn.infomodal.noConflict = function () {
        $.fn.infomodal = old
        return this
    }


    // InfoModal DATA-API
    $(document).on('click.bs.infomodal.data-api', '[data-toggle="infomodal"]', function (e) {
        // 监测所有拥有自定义属性data-toggle="modal"的元素上的点击事件，
        var $this = $(this)
        var href = $this.attr('href') // 获取href属性值
        var $target = $($this.attr('data-target') || (href && href.replace(/.*(?=#[^\s]+$)/, ''))) //strip for ie7
        //获取data-target属性值，如果没有，则获取href值，该值是所弹出元素的id

        var option = $target.data('infomodal') ? 'toggle' : $.extend({ remote: !/#/.test(href) && href }, $target.data(), $this.data())
        // 如果弹窗元素上设置了data-infomodal属性值，则option值是字符串toggle
        // 否则将remote值（如果有的话）、弹窗元素上的自定义属性值集合、触发元素上的自定义属性值集合，进行合并作为option选项对象

        e.preventDefault() // 阻止默认行为

        $target
            .infomodal(option, this)  // 给弹窗元素绑定infomodal插件（也就是实例化InfoModal），并传入option参数
            .one('hide', function () {
                $this.is(':visible') && $this.focus() // 定义一次hide事件，给所点击元素加上焦点
            })
    })

}(jQuery);
