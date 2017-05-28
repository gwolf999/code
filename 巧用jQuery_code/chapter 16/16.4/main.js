$(function(){
		var stopOnRow = false;   //�����Ƿ�ֹͣ
		var stopOnTable = false;  
		$('table').bind('rowclicked',function(event){
			$('table').removeClass('focused');  //ȥ����ʽ
			$(this).addClass('focused');       //������ʽ
			event.tableID = this.id;
			return !stopOnTable; 
		});
		$('tr')
			.click(function( event ){
				$(this).bubble('rowclicked');    //�������
				event.stopPropagation();       //ֹͣ����
			})
			.bind('rowclicked',function(event){
				$('tr').removeClass('selected');
				$(this).addClass('selected');
				event.rowID = this.id;
				return !stopOnRow;
			});
		$('#container').bind('rowclicked',function( event ){
			$(this).find('h3').text( 'row "'+event.rowID+'" clicked, from table "'+event.tableID+'"' );
		});
	});
