jQuery(document).ready(function(){
    //������һ��Google��ͼ��
    jQuery('#map1').jmap('init', {'mapType':'hybrid','mapCenter':[37.4419, -122.1419]});
    jQuery('#address-submit-1').click(function(){
        //���ڲ��ҵ�ͼ��ĳ��λ��
        jQuery('#map1').jmap('SearchAddress', {
            'query': jQuery('#address').val(),
            'returnType': 'getLocations'
        }, function(result, options) {
           var valid = Mapifies.SearchCode(result.Status.code);
           //���ҳɹ��Ļ���������ҳ������ӱ��
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
