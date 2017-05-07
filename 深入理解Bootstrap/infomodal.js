+function ($) {
    "use strict";

    // InfoModal�ඨ��
    var InfoModal = function (element, options) {
        this.$element = $(element);
        this.super = this.$element.data('bs.modal'); // ��ȡ�Զ�������bs.modal��ֵ;
        this.options = options;

        this.$element.on('click.confirm.infomodal', '[data-info="confirm"]', $.proxy(this.confirm, this));
        this.$element.on('click.cancel.infomodal', '[data-info="cancel"]', $.proxy(this.cancel, this));

        var that = this;// ��ֹ��Ⱦ����������ʱ����that

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

    //Ĭ������
    InfoModal.DEFAULTS = {
        closeAfterConfirm: false
        , closeAfterCancel: true
    }

    // ��ת����״̬
    InfoModal.prototype.toggle = function (_relatedTarget) {
        return this[!this.super.isShown ? 'show' : 'hide'](_relatedTarget)// ����ǹر�״̬����򿪵���������͹ر�
    }

    // �򿪵���
    InfoModal.prototype.show = function (_relatedTarget) {
        this.super.show(_relatedTarget);
    }

    // �رյ���
    InfoModal.prototype.hide = function (e) {
        if (e) e.preventDefault();// ����ֹð����Ϊ
        this.super.hide();
    }

    // ���ȷ�ϰ�ť����Ϊ
    InfoModal.prototype.confirm = function (e) {
        if (e) e.preventDefault();// ����ֹð����Ϊ

        var e = $.Event('confirm.bs.infomodal');
        this.$element.trigger(e);// ȷ��ǰ�������¼�����Ҫ���ڴ�����ش���

        if (e.isDefaultPrevented()) return;

        if (this.options.closeAfterConfirm) {
            this.hide();// ���������data-close-after-confirm=true��������رյ���
        }
    }

    // ���ȡ����ť����Ϊ
    InfoModal.prototype.cancel = function (e) {
        if (e) e.preventDefault();// ����ֹð����Ϊ

        var e = $.Event('cancel.bs.infomodal');
        this.$element.trigger(e);// ȡ��ǰ���ȴ����¼�

        if (e.isDefaultPrevented()) return;

        if (this.options.closeAfterCancel) {
            this.hide(e); // ���������data-close-after-cancel=true��������رյ���
        }
    }

    // InfoModal �������
    var old = $.fn.infomodal
    // �����������$.fn.infomodal���루�������Ļ����Ա���noConflict֮�󣬿��Լ���ʹ�ø��ϴ���

    $.fn.infomodal = function (option, _relatedTarget) {
        return this.each(function () {
            // ����ѡ�������������з��Ϲ����Ԫ��

            var $this = $(this)
            var data = $this.data('bs.infomodal') // ��ȡ�Զ�������bs.modal��ֵ
            var options = $.extend({}, InfoModal.DEFAULTS, $this.data(), typeof option == 'object' && option)
            // ��Ĭ�ϲ�����ѡ��������Ԫ���ϵ��Զ������ԣ�data-��ͷ������option�����������ֵ�ֵ�ϲ���һ����Ϊoptions����
            // ���ȼ�������Ĳ������ȼ�����ǰ��Ĳ���
            options.show = false; // Ĭ���ȹرգ�Ȼ���ں����ֹ���
            var modal = $this.modal(options, _relatedTarget);

            if (!data) $this.data('bs.infomodal', (data = new InfoModal(this, options)))
            // ���ֵ�����ڣ���InfoModalʵ������Ϊbs.infomodal��ֵ          

            if (typeof option == 'string') {
                data[option](_relatedTarget)
            }
            else {
                data.show(_relatedTarget);
            }
            // ���option������string�����ʾҪִ��ĳ������
            // ���紫����show����Ҫִ��InfoModalʵ����show������data["show"]�൱��data.show();
        })
    }

    $.fn.infomodal.Constructor = InfoModal; // ������������,����ͨ�������Ի�ȡ�������ʵ�ຯ��

    // InfoModal����ͻ
    $.fn.infomodal.noConflict = function () {
        $.fn.infomodal = old
        return this
    }


    // InfoModal DATA-API
    $(document).on('click.bs.infomodal.data-api', '[data-toggle="infomodal"]', function (e) {
        // �������ӵ���Զ�������data-toggle="modal"��Ԫ���ϵĵ���¼���
        var $this = $(this)
        var href = $this.attr('href') // ��ȡhref����ֵ
        var $target = $($this.attr('data-target') || (href && href.replace(/.*(?=#[^\s]+$)/, ''))) //strip for ie7
        //��ȡdata-target����ֵ�����û�У����ȡhrefֵ����ֵ��������Ԫ�ص�id

        var option = $target.data('infomodal') ? 'toggle' : $.extend({ remote: !/#/.test(href) && href }, $target.data(), $this.data())
        // �������Ԫ����������data-infomodal����ֵ����optionֵ���ַ���toggle
        // ����remoteֵ������еĻ���������Ԫ���ϵ��Զ�������ֵ���ϡ�����Ԫ���ϵ��Զ�������ֵ���ϣ����кϲ���Ϊoptionѡ�����

        e.preventDefault() // ��ֹĬ����Ϊ

        $target
            .infomodal(option, this)  // ������Ԫ�ذ�infomodal�����Ҳ����ʵ����InfoModal����������option����
            .one('hide', function () {
                $this.is(':visible') && $this.focus() // ����һ��hide�¼����������Ԫ�ؼ��Ͻ���
            })
    })

}(jQuery);
