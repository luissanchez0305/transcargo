<?php
?>
<div style="float:left;"><?= $data['text'] ?></div>
<div style="float:left; margin-left:20px;">
	<input type="text" id="searchVal" name="searchVal" size="10" style="width:100px;" />
</div>
<div style="float:right;">
	<input type="button" class="btn btn-primary" id="filter" value="Buscar" />
	<input type="hidden" id="mode" value="<?= $data['filter'] ?>" />
</div>
<script>
	$('#filter').click(function(){
		var sVal = $('#searchVal').val();
		var initalString = '';
		var queryString = window.location.search;

		if(queryString.indexOf('&') > -1) {
			var ampIndex = queryString.indexOf('&');
			
			if(queryString.indexOf('frame') > -1) {
				if(queryString.substring(queryString.indexOf('frame')).indexOf('&')>-1)
					ampIndex = ampIndex + queryString.substring(queryString.indexOf('frame')).indexOf('&') + 1;
				else
					ampIndex = ampIndex + queryString.substring(queryString.indexOf('frame')).length + 1;
			}
			else if(queryString.indexOf('last') > -1) {
				if(queryString.substring(queryString.indexOf('last')).indexOf('&')>-1)
					ampIndex = ampIndex + queryString.substring(queryString.indexOf('last')).indexOf('&') + 1;
				else
					ampIndex = ampIndex + queryString.substring(queryString.indexOf('last')).length + 1;
			}
			
			queryString = queryString.substring(0,ampIndex);
		}
		if(queryString.indexOf('?')>-1)
			initalString = '&';
		if(queryString.length == 0)
			queryString = '?list&';
		var fIndex = queryString.indexOf('f=');
		if(fIndex > -1)
			queryString = queryString.substring(0, fIndex - 1);
		location.href = queryString + initalString + 'f=' + sVal + '&s=' + $('#mode').val();
	});
</script>