var intOverallDelta = 0;
			$("#divScroll").mousewheel(function(objEvent, intDelta){
			    if (intDelta > 0){
			       intOverallDelta++;
				   $("#divScroll").html('up - (' + intOverallDelta + ')');
				}
			    else if (intDelta < 0){
					intOverallDelta--;
			       $("#divScroll").html('down - (' +  intOverallDelta + ')');
				}
			});
