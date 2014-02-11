<div style="float:left;"><?= $data['text'] ?></div>
<div><dl class="dl-horizontal">
<dt>Periodo:</dt>
<dd><select id="date">
<option value="">...</option>
<?php 
	foreach($data['dates'] as $item)
	{
	   	echo '<option value="'.date('Y-m-01',$item).'">'.date('F Y', $item).'</option>';			        	
	}?>	    
</select> <a class="hide" href="#">Otro</a></dd></dl></div>
<div><dl class="dl-horizontal">
<dt>Cliente:</dt>
<dd><select id="client">
<option value="-1">...</option>
<?php 
	foreach($data['clients'] as $item)
	{
		$val = $item['id'];
		$text = $item['name'];
		echo '<option value="'.$val.'">'.$text.'</option>';			        	
	}?>	    
</select> <a class="hide" href="#">Varios</a></dd></dl></div>
<div><dl class="dl-horizontal">
<dt>Conductor:</dt>
<dd><select id="driver">
<option value="-1">...</option>
<?php 
	foreach($data['drivers'] as $item)
	{
		$val = $item['id'];
		$text = $item['name'];
		echo '<option value="'.$val.'">'.$text.'</option>';			        	
	}?>	
</select> <a class="hide" href="#">Varios</a></dd></dl></div>
<div><dl class="dl-horizontal">
<dt>Bill of Lading:</dt>
<dd><select id="bill">
<option value="">...</option>
</select> <a class="hide" href="#">Varios</a></dd></dl></div>
<div><dl class="dl-horizontal">
<dt>Numero de Contenedor:</dt>
<dd><select id="container">
<option value="-1">...</option>
<?php 
	foreach($data['containers'] as $item)
	{
		$text = $item['name'];
		echo '<option value="'.$text.'">'.$text.'</option>';			        	
	}?>	
</select> <a class="hide" href="#">Varios</a></dd></dl></div>
<input type="hidden" id="mode" value="<?= $data['filter'] ?>" />
<div><dl class="dl-horizontal">
<dt><input type="checkbox"></dt>
<dd>Excluir ordenes con movimientos vinculados no terminados</dd></dl>
</div>
<div><dl class="dl-horizontal">
<dt></dt>
<dd><button id="filter" class="btn btn-primary" type="button">Filtrar</button></dd></dl>
</div>
<script>
$('#filter').click(function(){
	var date = $('#date').val();
	var client = $('#client').val();
	var driver = $('#driver').val();
	var container = $('#container').val();

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
	location.href = queryString + initalString + (date != '' ? 'date=' + date : '') + 
	(client != '-1' ? '&client=' + client : '') + (driver != '-1' ? '&driver=' + driver : '') +
	(container != '-1' ? '&container=' + container : '') + '&s=' + $('#mode').val();
});
</script>