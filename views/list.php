<html>
	<?=$data['header'];?>

	<style>
		.ui-widget-header {
			border: 0px;
			background: white/*{bgColorHeader}*/ 50%/*{bgHeaderXPos}*/ 50%/*{bgHeaderYPos}*/ repeat-x/*{bgHeaderRepeat}*/;
			color: #222/*{fcHeader}*/;
			font-weight: bold;
		}
	</style>
	<body>
		<div id="create"><?= $data["create"]?></div>
		<div id="edit"></div>
		<div id="filterContainer"></div>
	    <script>
	    $(document).ready(function(){ 
	    	modifyCreateButton(); 		       
		    $("#tabs").tabs({
			    active: <?= $data['tab']; ?>,
			    beforeActivate: function( event, ui ) {
					location.href = ui.newTab.attr('dest');
					return false;
			    }
			});
			$('.editDialog').click(function(){
				var $anchor = $(this).find('a');
				$.get('?edit', { id: $anchor.next().val() }, function(data){
					$('#edit').html(data);
					$('#edit').dialog('open');

					$('#btnEditOrder').click(function(){
						$.post('?input&mode=editOrder&' + $('#editOrderForm').serialize(), function(data){
							$('#orderResult').html(data);
							if(data == 'Guardado'){
								setTimeout(function(){$('#create').dialog('close');}, '3000');
								setTimeout(function(){ location.href='/' + window.location.search; }, '4000');
							}					
						});
					});
					$('#btnEditAllocation').click(function(){
						$.post('?input&mode=editAlloc&' + $('#editAllocationForm').serialize(), function(data){
							$('#allocationResult').html(data);
							if(data == 'Guardado'){
								setTimeout(function(){$('#create').dialog('close');}, '3000');
								setTimeout(function(){ location.href='/' + window.location.search; }, '4000');
							}					
						});				
					});			
				});
			});

			$('#filterContainer').dialog({ autoOpen: false, close: 
				function(event, ui){
					$('.actionsDD').children().each(function(index, value){
						var $this = $(this);
						if(index == 0) {
							$this.attr('selected', 'selected');
						}
						else {
							$this.removeAttr('selected');
						} 
					});
				}
			});
			$("#create").dialog({ autoOpen: false, minWidth: 700, minHeight: 300, maxHeight: 400 });
			$("#edit").dialog({ autoOpen: false, minWidth: 700, minHeight: 300, maxHeight: 400 });
			$('.createOrderLnk').click(function(){
				$('#create').dialog('open');
			});
			$('#itemsToMove').keydown(function(event){
				// Allow: backspace, delete, tab, escape, and enter
				if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 || 
				     // Allow: Ctrl+A
				    (event.keyCode == 65 && event.ctrlKey === true) || 
				    // Allow: home, end, left, right
				    (event.keyCode >= 35 && event.keyCode <= 39)) {
				         // let it happen, don't do anything
				         return;
				}
				else {
				    // Ensure that it is a number and stop the keypress
				    if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 ))
				    event.preventDefault(); 
				}
			});
			$('#itemsToMove').change(function(){
				var val = $(this).val();
				if(val.length == 0){
					val = 1;
					$(this).val('1');
				}
				modifyCreateButton();
				var currentContainersCount = $('#containersWrapper tbody').children('tr').length;
				if(currentContainersCount < val){
					var shippingOptions = '';
					for(var i = 0; i < $('#spareShippingList').children().length; i++){
						var item = $('#spareShippingList option:eq(' + i + ')');
						var itemVal = item.val();
						var itemText = item.text();
						shippingOptions += '<option value="' + itemVal + '">' + itemText + '</option>';
					}
					for(var i = 0; i < (val - currentContainersCount); i++){
						var itemIndex = i + currentContainersCount;
						$('#containersWrapper tbody').append('<tr><td style="width: 274px; ">N° ' + (itemIndex + 1) + '&nbsp; '+
							'<input id="containerCode['+itemIndex+']" type="text" name="containerCode['+itemIndex+']" /></td>'+
							'<td style="width: 191px;' + ($('#moveType').val() == '1' ? ' display:none;' : '') + '">Tipo: <select name="containerType['+itemIndex+']" id="containerType['+itemIndex+']" style="width: 121px">'+
							shippingOptions +
							'</select></td>'+
							'<td style="width: 90px;"><label class="radio"><input type="radio" name="isFull['+itemIndex+']" id="isFull['+itemIndex+']" value="1" checked="checked"' + ($('#moveType').val() == '1' ? ' disabled="disabled";' : '') + ' />'+
							'Lleno</label></td>'+
							'<td><label class="radio"><input type="radio" name="isFull['+itemIndex+']" id="isFull['+itemIndex+']" value="0"' + ($('#moveType').val() == '1' ? ' disabled="disabled";' : '') + ' />'+
							'Vacio</label></td></tr>');
					}					
				}
				else if(currentContainersCount > val){
					//eliminar de abajo hacia arriba
					for(var i = 0; i < (currentContainersCount - val); i++){
						$('#containersWrapper tbody tr:last').remove();
					}
				}
			});
			$('#createOrder').click(function(){
				// validate date, from diferente de to, containerBL
				var valid = true;
				// validar fecha
				if($('#dateCreate').val().length == 0){
					$('#dateCreate').addClass('error-text');
					valid = false;
				}
				else {
					$('#dateCreate').removeClass('error-text');					
				}

				// validar BL
				/*if($('#containerBL').val().length == 0){
					$('#containerBL').addClass('error-text');
					valid = false;
				}
				else {
					$('#containerBL').removeClass('error-text');
				}*/

				// validar from y to sean diferentes				
				if($('#from').val() == $('#to').val()){
					$('#from').addClass('error-text');
					$('#to').addClass('error-text');
					valid = false;
				} 
				else {
					$('#from').removeClass('error-text');
					$('#to').removeClass('error-text');	
				}

				// validar que todos los contenedores tengan su codigo
				/*$('input[id^="containerCode"]').each(function(){
					var $this = $(this);
					if($this.val().length == 0){
						valid = false;
						$this.addClass('error-text');
					}
					else {
						$this.removeClass('error-text');
					}
				});*/
				
				if(valid)
					$.post('?input&mode=insert&' + $('#createOrderForm').serialize(), function(data){
						$('#result').html(data);
						if(data == 'Guardado'){
							setTimeout(function(){$('#create').dialog('close');}, '3000');
							setTimeout(function(){ location.href='/' + window.location.search; }, '4000');
						}
					});
			});
			$('#moveType').change(function(){
				if($(this).val() == '1'){
					$('select[id^="containerType"]').parent().hide();
					$('input[id^="isFull"]').attr('disabled','disabled');
					$('input[id^="isFull"][value="1"]')[0].setAttribute('checked','checked');
					for(var i = 1; i < $('#itemsToMove').val(); i++){
						$('#containersWrapper tbody tr:eq('+i+')').hide();
						$('input[id^="containerCode"]')[i].setAttribute('disabled','disabled');
						$('select[id^="containerType"]')[i].setAttribute('disabled','disabled');
						$('input[id^="isFull"]')[i].setAttribute('disabled','disabled');
					}
					modifyCreateButton();
				}
				else {
					$('select[id^="containerType"]').parent().show();
					$('input[id^="isFull"]').removeAttr('disabled');	
					for(var i = 1; i < $('#itemsToMove').val(); i++){
						$('#containersWrapper tbody tr:eq('+i+')').show();
						$('input[id^="containerCode"]')[i].removeAttribute('disabled');
						$('select[id^="containerType"]')[i].removeAttribute('disabled');
						$('input[id^="isFull"]')[i].removeAttribute('disabled');
					}			
					modifyCreateButton();
				}					
			});
			$('#from').change(function(){
				modifyCreateButton();
			});
			$('#to').change(function(){
				modifyCreateButton();
			});
			$('.actionsDD').change(function(){
				var optionVal = $(this).val();
				if(optionVal.length > 0) {
					var optionArray = optionVal.split(',');
					if(optionArray[1] != '0'){
						var _text = '';
						if(optionArray[3])
							_text = optionArray[3];
						$.get('?filter', { filter: optionArray[0], text: _text }, function(data){					
							$('#filterContainer').html(data);
							if(optionArray[1] == 1){
								$('#filterContainer').dialog("option", "width", 200);
								$('#filterContainer').dialog("option", "minHeight", 100);
							}
							else if(optionArray[1] == 2){
								$('#filterContainer').dialog("option", "width", 550);
								$('#filterContainer').dialog("option", "minHeight", 460);								
							}
							$('#filterContainer').dialog("option", "title", optionArray[2]);
							$('#filterContainer').dialog('open');
						});
					}
					else
					{
						if(window.location.search.length > 0)
							window.open('/' + window.location.search.replace('?list','?export'), '_blank');
						else
							window.open('/?export');	
					}
				}
			});
			var fTypes = getParameterByName('ftype').split(',');
			for(var i = 0; i < fTypes.length; i++) {
				$('#cb_' + fTypes[i]).attr('checked','checked');
			}
			$('#cb_wDocument,#cb_nDocument,#cb_full,#cb_empty').click(function(){
				var type = getParameterByName('ftype');
				var qString = window.location.search;
				var removeThis = '&ftype=' + type;
				qString = qString.replace(removeThis,'');
				var filter = '';
				$('#cb_wDocument,#cb_nDocument,#cb_full,#cb_empty').each(function(){
					var $this = $(this);
					if($this.attr('checked'))
						filter += $(this).val() + ',';
				});
				location.href = '/'+qString+'&ftype='+filter.substring(0, filter.length-1);
			});
	    });

	    function getParameterByName(name) {
	    	name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
	    	var regexS = "[\\?&]" + name + "=([^&#]*)";
	    	var regex = new RegExp(regexS);
	    	var results = regex.exec(window.location.search);
	    	if (results == null)
	    		return "";
	    	else
	    		return decodeURIComponent(results[1].replace(/\+/g, " "));
	    }
	    function modifyContainerDisplay(obj){
		    var val = obj.value;
			if(val == '1'){
				$('input[name="containerBL"]').parent().hide();
				$('select[name="shippingCo"]').parent().hide();
				$('select[name="containerType"]').parent().hide();
				$('input[name="containerNumber"]').prev().html('Furgon:');
				$('#isFull[value="1"]').attr('checked','checked');
				$('input[name="isFull"]').attr('disabled','disabled');
			}
			else{
				$('input[name="containerBL"]').parent().show();
				$('select[name="shippingCo"]').parent().show();
				$('select[name="containerType"]').parent().show();
				$('input[name="containerNumber"]').prev().html('Container:');
				$('input[name="isFull"]').removeAttr('disabled');
			}
	    }
	    function modifyCreateButton() {
	    	if($('#moveType').val() == 1){
				$('#createOrder').html('Crear (1) Orden');		    	
	    	}
	    	else {
	    		var val = $('#itemsToMove').val();
		    	var fromText = $('#from option:selected').text();
		    	var toText = $('#to option:selected').text();
		    	if(fromText.indexOf('Puerto') > -1 && toText.indexOf('Bodega') > -1)
		    		$('#createOrder').html('Crear (' + (val * 2) + ') Ordenes');
		    	else
		    		$('#createOrder').html('Crear (' + (val) + ') Ordenes');
			    	
	    	}
	    }	    
	    </script>	
		<?= $data['uppercontent']; ?>
		<div id="tabs" class="container"><div class="hero-unit">
			<div>
			    <ul class="nav nav-tabs">
			        <li dest="/?list"><a href="#tabs-1">Solicitudes</a></li>
			        <li dest="/?list&frame=today"><a href="#tabs-2">Programacion</a></li>
			        <li dest="/?list&last=1"><a href="#tabs-3">Gestion</a></li>
			    </ul>
		    </div>
		   <div id="tabs-1" style="width: 100%; margin-left: -30px;">
		   	<table class="table table-size">
		   		<thead>
		   		</thead>
		   		<tbody>
		   			<tr>
		   			<td style="height: 37px; width: 50px;">&nbsp;</td>
					<td style="height: 37px; width: 30px;">
						<div class="btn-group">
						    <button class="btn btn-info<?= $data['option'] == 'today' ? ' active' : '' ?>" onclick="location.href='/?list&main=today'">Hoy</button>
						    <button class="btn btn-info<?= $data['option'] == 'all' ? ' active' : '' ?>" onclick="location.href='/?list&main=all'">Todas</button>
					    </div>		   				
		   			</td>
		   			</tr>
		   		</tbody>
		   	</table>
			   <div>			
					<?= $data['actionsdropdown']?>
					<?= $data['createorderlink']; ?>
				</div>		   
		        <?= $data['list1']?>
		        <?= $data['pagination1']; ?>
		    </div>
		    
		    <div id="tabs-2" class="nav nav-tabs" style="width: 914px; padding-left: 5px;">
				<table class="table table-size">
				<thead>
				</thead>
				<tbody>
				<tr>				
					<td style="height: 37px; width: 154px;"><input type="checkbox" id="cb_wDocument" value="wDocument"/>Con Documento<p><input type="checkbox" id="cb_nDocument" value="nDocument"/>Sin Documento</p></td>
					<td style="height: 37px; width: 134px;"><input type="checkbox" id="cb_full" value="full"/>Llenos<p><input type="checkbox" id="cb_empty" value="empty"/>Vacios</p></td>
					<td style="height: 37px; width: 30px;">
					<div class="btn-group">
					    <button class="btn btn-info<?= $data['option'] == 'yesterday' ? ' active' : '' ?>" onclick="location.href='/?list&frame=yesterday'">Ayer</button>
					    <button class="btn btn-info<?= $data['option'] == 'today' ? ' active' : '' ?>" onclick="location.href='/?list&frame=today'">Hoy</button>
					    <button class="btn btn-info<?= $data['option'] == 'tomorrow' ? ' active' : '' ?>" onclick="location.href='/?list&frame=tomorrow'">Mañana</button>
					    <button class="btn btn-info<?= $data['option'] == 'weekly' ? ' active' : '' ?>" onclick="location.href='/?list&frame=weekly'">Semanal</button>
					    </div></td>
				<td style="width: 56px" class="hide">
				<div class="btn-group pagination-mini">
				<button class="btn btn-info btn-mini"><<</button>
				<button class="btn btn-info btn-mini table-size">>></button>
				</div>
				</td>
				<td><?= $data['dateRange']; ?></td>
				</tr>
				</tbody>
				</table>		    	
				<div>
					<?= $data['actionsdropdown']?>
		    		<?= $data['createorderlink']; ?>
		    	</div>
		    	<?= $data['list2']; ?>
		        <?= $data['pagination2']; ?>
		    </div>
		    
		    <div id="tabs-3" class="nav nav-tabs">
   				<div>
					<?= $data['actionsdropdown']?>
					<?= $data['createorderlink']; ?>
				</div>	
		    	<?= $data['list3']; ?>
		        <?= $data['pagination3']; ?>
		    </div>
		</div>		
		</div>
		<?= $data['footer']; ?>
	</body>
</html>