$(function(){
		var stopOnRow = false;   //设置是否停止
		var stopOnTable = false;  
		$('table').bind('rowclicked',function(event){
			$('table').removeClass('focused');  //去除样式
			$(this).addClass('focused');       //增加样式
			event.tableID = this.id;
			return !stopOnTable; 
		});
		$('tr')
			.click(function( event ){
				$(this).bubble('rowclicked');    //将表格变大
				event.stopPropagation();       //停止动画
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
