function lookup(inputString) {
    if(inputString.length == 0) {
        //��û�������ʱ��������ʾ��
        $(��#suggestions��).hide();
    } else {
        //�������post�������÷������˵ķ�������ȡ�Դ˴�ͷ���ַ�������
        $.post("rpc.php", {queryString: ""+inputString+""}, function(data){
            //���ز�Ϊ�յĻ��������ݷ��õ���ʾ����
            if(data.length >0) {
                $(��#suggestions��).show();
                $(��#autoSuggestionsList��).html(data);
            }
        });
    }
} // lookup
function fill(thisValue) {
    $(��#inputString��).val(thisValue);
   $(��#suggestions��).hide();
}
