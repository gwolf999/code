+function ($) {
    "use strict";

    // FormModal类定义
    var FormModal = function (element, options) {
        this.$element = $(element);
        this.super = this.$element.data('bs.modal'); // 获取自定义属性bs.modal的值;
        this.options = options;

        this.$element.on('click.submit.formmodal', '[data-form="submit"]', $.proxy(this.submit, this));
        this.$element.on('click.reset.formmodal', '[data-form="reset"]', $.proxy(this.reset, this));
        this.$element.on('click.cancel.formmodal', '[data-form="cancel"]', $.proxy(this.cancel, this));

        var that = this;// 防止污染作用域，用临时变量that

        this.$element.on("show.bs.modal", function (e) {
            that.$element.trigger(e = $.Event('show.bs.formmodal'));
            if (e.isDefaultPrevented()) return;
        });

        this.$element.on("shown.bs.modal", function (e) {
            that.$form = that.$element.find('form');
            that.$element.trigger(e = $.Event('shown.bs.formmodal'));
            if (e.isDefaultPrevented()) return;
        });

        this.$element.on("hide.bs.modal", function (e) {
            that.$element.trigger(e = $.Event('hide.bs.formmodal'));
            if (e.isDefaultPrevented()) return;
        });

        this.$element.on("hidden.bs.modal", function (e) {
            that.$element.trigger(e = $.Event('hidden.bs.formmodal'));
            if (e.isDefaultPrevented()) return;
        });

    }

    //默认设置
    FormModal.DEFAULTS = {
        cacheForm: false,
        closeAfterCancel: true
    }

    // 反转弹窗状态
    FormModal.prototype.toggle = function (_relatedTarget) {
        return this[!this.super.isShown ? 'show' : 'hide'](_relatedTarget)// 如果是关闭状态，则打开弹窗，否则就关闭
    }

    // 打开弹窗
    FormModal.prototype.show = function (_relatedTarget) {
        this.super.show(_relatedTarget);
    }

    // 关闭弹窗
    FormModal.prototype.hide = function (e) {
        if (e) e.preventDefault();// 先阻止冒泡行为
        this.super.hide();
    }

    // 点击确认按钮的行为
    FormModal.prototype.submit = function (e) {
        if (e) e.preventDefault();// 先阻止冒泡行为

        this.$element.trigger(e = $.Event('beforeSubmit.bs.formmodal'));// 提交前触发事件，主要用于处理相关代码
        if (e.isDefaultPrevented()) return;

        this.$form.submit();

        this.$element.trigger(e = $.Event('afterSubmit.bs.formmodal'));// 提交后触发事件，主要用于处理相关代码
        if (e.isDefaultPrevented()) return;
    }

    // 点击重置按钮的行为
    FormModal.prototype.reset = function (e) {
        if (e) e.preventDefault();// 先阻止冒泡行为

        var resetAction = function () {
            this.$element.trigger(e = $.Event('beforeReset.bs.formmodal'));// 重置前触发事件
            if (e.isDefaultPrevented()) return;

            this.$form.each(function () {
                this.reset(); // jQuery不支持reset，需要转换为DOM对象再调用原生reset方法
            });

            this.$element.trigger(e = $.Event('afterReset.bs.formmodal'));// 重置后触发事件
            if (e.isDefaultPrevented()) return;
        }

        if (this.super.isShown) return resetAction.call(this);

        this.$element.one("shown.bs.formmodal", $.proxy(resetAction, this));
        this.show();
    }

    // 点击取消按钮的行为
    FormModal.prototype.cancel = function (e) {
        if (e) e.preventDefault();// 先阻止冒泡行为

        var e = $.Event('cancel.bs.formmodal');
        this.$element.trigger(e);// 取消前，先触发事件

        if (e.isDefaultPrevented()) return;

        if (this.options.closeAfterCancel) {
            this.hide(e); // 如果设置了data-close-after-cancel=true参数，则关闭弹窗
        }
    }

    // formmodal 插件定义
    var old = $.fn.formmodal
    // 保留其它库的$.fn.formmodal代码（如果定义的话，以便在noConflict之后，可以继续使用该老代码

    $.fn.formmodal = function (option, _relatedTarget) {
        return this.each(function () {
            // 根据选择器，遍历所有符合规则的元素

            var $this = $(this)

            var options = $.extend({}, FormModal.DEFAULTS, $this.data(), typeof option == 'object' && option)
            // 将默认参数、选择器所在元素上的自定义属性（data-开头）、和option参数，这三种的值合并再一起，作为options参数
            // 优先级：后面的参数优先级高于前面的参数

            var data = options.cacheForm && $this.data('bs.formmodal') // 获取自定义属性bs.modal的值

            options.show = false;

            if (!options.cacheForm) { // 如果不用缓存，则先清空实例，然后重新load远程的html内容。
                $this.data('bs.modal', null);
            }

            $this.modal(options, _relatedTarget);

            if (!data) $this.data('bs.formmodal', (data = new FormModal(this, options)))
            // 如果值不存在，则将formmodal实例设置为bs.formmodal的值          

            if (typeof option == 'string') {
                data[option](_relatedTarget)
            }
            else {
                data.show(_relatedTarget);
            }
            // 如果option传递了string，则表示要执行某个方法
            // 比如传入了show，则要执行formmodal实例的show方法，data["show"]相当于data.show();

        })
    }

    $.fn.formmodal.Constructor = FormModal; // 重设插件构造器,可以通过该属性获取插件的真实类函数

    // formmodal防冲突
    $.fn.formmodal.noConflict = function () {
        $.fn.formmodal = old
        return this
    }


    // formmodal DATA-API
    $(document).on('click.bs.formmodal.data-api', '[data-toggle="formmodal"]', function (e) {
        // 监测所有拥有自定义属性data-toggle="modal"的元素上的点击事件，
        var $this = $(this)
        var href = $this.attr('href') // 获取href属性值
        var $target = $($this.attr('data-target') || (href && href.replace(/.*(?=#[^\s]+$)/, ''))) //strip for ie7
        //获取data-target属性值，如果没有，则获取href值，该值是所弹出元素的id

        var option = $target.data('formmodal') ? 'toggle' : $.extend({ remote: !/#/.test(href) && href }, $target.data(), $this.data())
        // 如果弹窗元素上设置了data-formmodal属性值，则option值是字符串toggle
        // 否则将remote值（如果有的话）、弹窗元素上的自定义属性值集合、触发元素上的自定义属性值集合，进行合并作为option选项对象

        e.preventDefault() // 阻止默认行为

        $target
            .formmodal(option, this)  // 给弹窗元素绑定formmodal插件（也就是实例化formmodal），并传入option参数
            .one('hide', function () {
                $this.is(':visible') && $this.focus() // 定义一次hide事件，给所点击元素加上焦点
            })
    })

}(jQuery);
