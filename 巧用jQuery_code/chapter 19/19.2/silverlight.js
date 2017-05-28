jquery.silverlight.js
jQuery.fn.extend({
    silverlight: function(opts) {
        _opts = jQuery.extend({
            background: 'white',   //���屳��Ϊ��ɫ
            minRuntimeVersion: '2.0.31005.0',  //�������е���С�汾
            autoUpgrade: true,  //�����Ƿ��Զ�����
            windowless: false,  //�Ƿ��д���
            width: '100%',    //���
            height: '100%'   //�߶�
        }, opts);
      //��������silverlight����Ϊ�գ��򱨴�
       if (!_opts.source || _opts.source == '') throw new error('��source�����Բ���Ϊ�� ');
        var obj = $('<object>').attr({
            data: 'data:application/x-silverlight-2,',
            type: 'application/x-silverlight-2',
            width: _opts.width,
            height: _opts.height
        });
        jQuery.each(_opts, function(name, value) {
            if (name == 'width' || name == 'height') return;
            obj.append(
                $('<param>').attr({
                    name: name,
                    value: value
                })
            );
        });
        //���û�а�װsilverlight����Ļ�����ʾ��װsilverlight���
        obj.append(
            $('<a>').attr('href', 'http://go.microsoft.com/fwlink/?LinkID=124807').css('text-decoration', 'none').append(
                $("<img>").attr({
                    src: 'http://go.microsoft.com/fwlink/?LinkId=108181',
                    alt: '���̰�װ Microsoft Silverlight'
                }).css('border-style', 'none')
            )
        );
        $(this).append(obj);
    }
});
