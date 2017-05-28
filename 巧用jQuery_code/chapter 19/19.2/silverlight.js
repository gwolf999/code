jquery.silverlight.js
jQuery.fn.extend({
    silverlight: function(opts) {
        _opts = jQuery.extend({
            background: 'white',   //定义背景为白色
            minRuntimeVersion: '2.0.31005.0',  //定义运行的最小版本
            autoUpgrade: true,  //设置是否自动升级
            windowless: false,  //是否有窗口
            width: '100%',    //宽度
            height: '100%'   //高度
        }, opts);
      //如果传入的silverlight对象为空，则报错
       if (!_opts.source || _opts.source == '') throw new error('「source」属性不能为空 ');
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
        //如果没有安装silverlight插件的话，提示安装silverlight插件
        obj.append(
            $('<a>').attr('href', 'http://go.microsoft.com/fwlink/?LinkID=124807').css('text-decoration', 'none').append(
                $("<img>").attr({
                    src: 'http://go.microsoft.com/fwlink/?LinkId=108181',
                    alt: '立刻安装 Microsoft Silverlight'
                }).css('border-style', 'none')
            )
        );
        $(this).append(obj);
    }
});
