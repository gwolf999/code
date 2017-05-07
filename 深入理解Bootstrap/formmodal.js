+function ($) {
    "use strict";

    // FormModal�ඨ��
    var FormModal = function (element, options) {
        this.$element = $(element);
        this.super = this.$element.data('bs.modal'); // ��ȡ�Զ�������bs.modal��ֵ;
        this.options = options;

        this.$element.on('click.submit.formmodal', '[data-form="submit"]', $.proxy(this.submit, this));
        this.$element.on('click.reset.formmodal', '[data-form="reset"]', $.proxy(this.reset, this));
        this.$element.on('click.cancel.formmodal', '[data-form="cancel"]', $.proxy(this.cancel, this));

        var that = this;// ��ֹ��Ⱦ����������ʱ����that

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

    //Ĭ������
    FormModal.DEFAULTS = {
        cacheForm: false,
        closeAfterCancel: true
    }

    // ��ת����״̬
    FormModal.prototype.toggle = function (_relatedTarget) {
        return this[!this.super.isShown ? 'show' : 'hide'](_relatedTarget)// ����ǹر�״̬����򿪵���������͹ر�
    }

    // �򿪵���
    FormModal.prototype.show = function (_relatedTarget) {
        this.super.show(_relatedTarget);
    }

    // �رյ���
    FormModal.prototype.hide = function (e) {
        if (e) e.preventDefault();// ����ֹð����Ϊ
        this.super.hide();
    }

    // ���ȷ�ϰ�ť����Ϊ
    FormModal.prototype.submit = function (e) {
        if (e) e.preventDefault();// ����ֹð����Ϊ

        this.$element.trigger(e = $.Event('beforeSubmit.bs.formmodal'));// �ύǰ�����¼�����Ҫ���ڴ�����ش���
        if (e.isDefaultPrevented()) return;

        this.$form.submit();

        this.$element.trigger(e = $.Event('afterSubmit.bs.formmodal'));// �ύ�󴥷��¼�����Ҫ���ڴ�����ش���
        if (e.isDefaultPrevented()) return;
    }

    // ������ð�ť����Ϊ
    FormModal.prototype.reset = function (e) {
        if (e) e.preventDefault();// ����ֹð����Ϊ

        var resetAction = function () {
            this.$element.trigger(e = $.Event('beforeReset.bs.formmodal'));// ����ǰ�����¼�
            if (e.isDefaultPrevented()) return;

            this.$form.each(function () {
                this.reset(); // jQuery��֧��reset����Ҫת��ΪDOM�����ٵ���ԭ��reset����
            });

            this.$element.trigger(e = $.Event('afterReset.bs.formmodal'));// ���ú󴥷��¼�
            if (e.isDefaultPrevented()) return;
        }

        if (this.super.isShown) return resetAction.call(this);

        this.$element.one("shown.bs.formmodal", $.proxy(resetAction, this));
        this.show();
    }

    // ���ȡ����ť����Ϊ
    FormModal.prototype.cancel = function (e) {
        if (e) e.preventDefault();// ����ֹð����Ϊ

        var e = $.Event('cancel.bs.formmodal');
        this.$element.trigger(e);// ȡ��ǰ���ȴ����¼�

        if (e.isDefaultPrevented()) return;

        if (this.options.closeAfterCancel) {
            this.hide(e); // ���������data-close-after-cancel=true��������رյ���
        }
    }

    // formmodal �������
    var old = $.fn.formmodal
    // �����������$.fn.formmodal���루�������Ļ����Ա���noConflict֮�󣬿��Լ���ʹ�ø��ϴ���

    $.fn.formmodal = function (option, _relatedTarget) {
        return this.each(function () {
            // ����ѡ�������������з��Ϲ����Ԫ��

            var $this = $(this)

            var options = $.extend({}, FormModal.DEFAULTS, $this.data(), typeof option == 'object' && option)
            // ��Ĭ�ϲ�����ѡ��������Ԫ���ϵ��Զ������ԣ�data-��ͷ������option�����������ֵ�ֵ�ϲ���һ����Ϊoptions����
            // ���ȼ�������Ĳ������ȼ�����ǰ��Ĳ���

            var data = options.cacheForm && $this.data('bs.formmodal') // ��ȡ�Զ�������bs.modal��ֵ

            options.show = false;

            if (!options.cacheForm) { // ������û��棬�������ʵ����Ȼ������loadԶ�̵�html���ݡ�
                $this.data('bs.modal', null);
            }

            $this.modal(options, _relatedTarget);

            if (!data) $this.data('bs.formmodal', (data = new FormModal(this, options)))
            // ���ֵ�����ڣ���formmodalʵ������Ϊbs.formmodal��ֵ          

            if (typeof option == 'string') {
                data[option](_relatedTarget)
            }
            else {
                data.show(_relatedTarget);
            }
            // ���option������string�����ʾҪִ��ĳ������
            // ���紫����show����Ҫִ��formmodalʵ����show������data["show"]�൱��data.show();

        })
    }

    $.fn.formmodal.Constructor = FormModal; // ������������,����ͨ�������Ի�ȡ�������ʵ�ຯ��

    // formmodal����ͻ
    $.fn.formmodal.noConflict = function () {
        $.fn.formmodal = old
        return this
    }


    // formmodal DATA-API
    $(document).on('click.bs.formmodal.data-api', '[data-toggle="formmodal"]', function (e) {
        // �������ӵ���Զ�������data-toggle="modal"��Ԫ���ϵĵ���¼���
        var $this = $(this)
        var href = $this.attr('href') // ��ȡhref����ֵ
        var $target = $($this.attr('data-target') || (href && href.replace(/.*(?=#[^\s]+$)/, ''))) //strip for ie7
        //��ȡdata-target����ֵ�����û�У����ȡhrefֵ����ֵ��������Ԫ�ص�id

        var option = $target.data('formmodal') ? 'toggle' : $.extend({ remote: !/#/.test(href) && href }, $target.data(), $this.data())
        // �������Ԫ����������data-formmodal����ֵ����optionֵ���ַ���toggle
        // ����remoteֵ������еĻ���������Ԫ���ϵ��Զ�������ֵ���ϡ�����Ԫ���ϵ��Զ�������ֵ���ϣ����кϲ���Ϊoptionѡ�����

        e.preventDefault() // ��ֹĬ����Ϊ

        $target
            .formmodal(option, this)  // ������Ԫ�ذ�formmodal�����Ҳ����ʵ����formmodal����������option����
            .one('hide', function () {
                $this.is(':visible') && $this.focus() // ����һ��hide�¼����������Ԫ�ؼ��Ͻ���
            })
    })

}(jQuery);
