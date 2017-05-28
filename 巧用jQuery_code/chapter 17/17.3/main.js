function findValue(li) {
  	if( li == null ) 
return alert("No match!");
    	if( !!li.extra ) 
var sValue = li.extra[0];
  	else 
var sValue = li.selectValue;
  }
  function selectItem(li) {
    	findValue(li);
  }
  function formatItem(row) {
    	return row[0] + " (id: " + row[1] + ")";
  }
  function lookupAjax(){
  	var oSuggest = $("#CityAjax")[0].autocompleter;
    oSuggest.findValue();
  	return false;
  }
  function lookupLocal(){
    	var oSuggest = $("#CityLocal")[0].autocompleter;
    	oSuggest.findValue();
    	return false;
  }
      $("#CityAjax").autocomplete(
      "autocomplete.php",
      {
  			delay:10,
  			minChars:2,
  			matchSubset:1,
  			matchContains:1,
  			cacheLength:10,
  			onItemSelect:selectItem,
  			onFindValue:findValue,
  			formatItem:formatItem,
  			autoFill:true
  		}
    );
