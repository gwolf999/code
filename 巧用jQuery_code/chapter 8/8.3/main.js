jQuery(document).ready(function(){
    //加载了一个Google地图。
    jQuery('#map1').jmap('init', {'mapType':'hybrid','mapCenter':[37.4419, -122.1419]});
    jQuery('#address-submit-1').click(function(){
        //用于查找地图上某个位置
        jQuery('#map1').jmap('SearchAddress', {
            'query': jQuery('#address').val(),
            'returnType': 'getLocations'
        }, function(result, options) {
           var valid = Mapifies.SearchCode(result.Status.code);
           //查找成功的话，可以在页面上添加标记
            if (valid.success) {
            jQuery.each(result.Placemark, function(i, point){
                jQuery('#map1').jmap('AddMarker',{
                        'pointLatLng':[point.Point.coordinates[1], point.Point.coordinates[0]],
                        'pointHTML':point.address
                    });
                });
            } else {
                jQuery('#address').val(valid.message);
            }
        });
        return false;	
    });
});
