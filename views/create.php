
	<script>
	    $(function() {
	        $( "#dateCreate" ).datepicker();
	    });
	</script> 
		<form method="post" action="/?input" id="createOrderForm">
		  <div><h2>Detalles del movimiento</h2></div>
	      <table class="table table table-bordered table-size">
	        <thead>
			</thead>	
			<tbody>
				<tr>
				<td colspan="2" style="height: 77px;"><div><dl class="dl-horizontal">
				<dt>Cliente:</dt>
				<dd><select name="client">
		          <?php 
			        foreach($data['clients'] as $item)
			        {
			        	$val = $item['id'];
			        	$text = $item['name'];
			        	echo '<option value="'.$val.'">'.$text.'</option>';			        	
			        }?>	          
		          </select> </dd></dl></div>
				</td>
				</tr>
				<tr>
				<td style="width: 195px"><div>Fecha Del Movimientos: <input type="text" id="dateCreate" name="dateCreate" size="20" style="width:100px;">
				<div>Tipo de Movimiento: <select id="moveType" name="moveType">
				          <?php 
					        foreach($data['moveTypes'] as $item)
					        {
					        	$val = $item['id'];
					        	$text = $item['name'];
					        	echo '<option value="'.$val.'" '.($val == 2 ? ' selected="selected"' : '').'>'.$text.'</option>';	        	
					        }?>
				          </select></td>
				<td style="width: 235px"><div>Origen: <select id="from" name="from">
				          <?php 
					        foreach($data['places'] as $item)
					        {
					        	$type = $item['type'];
					        	$val = $item['id'];
					        	$text = $item['name'];
					        	echo '<option value="'.$val.'">'.$text.' - '.$type.'</option>';			        	
					        }?>
				          </select></div>
				<div>Destino: <select id="to" name="to">
				          <?php 
					        foreach($data['places'] as $item)
					        {
					        	$type = $item['type'];
					        	$val = $item['id'];
					        	$text = $item['name'];
					        	echo '<option value="'.$val.'">'.$text.' - '.$type.'</option>';		        	
					        }?>	          
				          </select></div></td>
				</tr>
			</tbody>  
			</table>
			<table class="table table table-bordered table-size">
				<tbody>
					<tr>
						<?php 
							$ind = 0;
							$isOpened = FALSE;
							foreach($data['autoComments'] as $item)
							{
								$val = $item['id'];
								$text = $item['name'];
								$section = $item['section'];
								if(!$isOpened && $ind < 1){
									$isOpened = TRUE;
									echo '<td style=" width: 229px;">';
								}
								
								if(!$isOpened && $ind >= 2 && $ind < 4){
									$isOpened = TRUE;
									echo '<td style=" width: 229px;">';									
								}
								
								if(!$isOpened && $ind >= 4){
									$isOpened = TRUE;	
									echo '<td>';										
								}
								
								echo '<div><label class="radio"><input type="radio" name="autoComment'.$section.'" id="autoComment'.$section.'" value="'.$val.'" />'.$text.'</label></div>';
								
								if($isOpened && ($ind == 1 || $ind == 3 || $ind == (count($data['autoComments']) - 1))){
									$isOpened = FALSE;
									echo '</td>';
								}
								$ind += 1;
							}
						?>				
					</tr>
				</tbody>
			</table>			
			<table class="table table table-size">
				<tbody>
					<tr>
						<td colspan="2"><div><dl class="dl-horizontal">
						<dt>Cantidad de Conte.</dt>
						<dd><input id="itemsToMove" name="itemsToMove" type="text" size="2" style="width:55px;" value="1" /> </dd></dl></div>
						</td>
					</tr>
					<tr>
						<td style="width: 195px"><div>B/L <input id="containerBL" name="containerBL" type="text" size="10" style="width:100px;" /></div></td>
						<td style="width: 235px"><div>Naviera: <select name="shipping">
					          <?php 
						        foreach($data['shippings'] as $item)
						        {
						        	$val = $item['id'];
						        	$text = $item['name'];
						        	echo '<option value="'.$val.'">'.$text.'</option>';		        	
						        }?>	          
					          </select></div></td>
					</tr>
				</tbody>
			</table>
			<table id="containersWrapper" class="table table table-size">
				<tbody>
					<tr>
						<td style="width: 274px; ">N° 1&nbsp; <input id="containerCode[0]" type="text" name="containerCode[0]" /></td>
						<td style="width: 191px;">Tipo: <select name="containerType[0]" id="containerType[0]" style="width: 121px">
						          <?php 
							        foreach($data['containerTypes'] as $item)
							        {
							        	$val = $item['id'];
							        	$text = $item['name'];
							        	echo '<option value="'.$val.'">'.$text.'</option>';		        	
							        }?>	          
						          </select></td>
						<td style="width: 90px;"><label class="radio">
						<input type="radio" name="isFull[0]" id="isFull[0]" value="1" checked="checked" /> 
							Lleno</label></td>
						<td><label class="radio">
						<input type="radio" name="isFull[0]" id="isFull[0]" value="0" />
							Vacio</label></td>
					</tr>
				</tbody>
			</table>	
			<table class="table table table-size">
				<tbody>
					<tr>
						<td style="width: 122px"><div>Comentarios:&nbsp;<form class="bs-docs-example form-inline">
						<textarea id="comment" name="comment" style="width: 325px; height: 70px"></textarea>
						</form></div></td>
						<td><div>Estado:&nbsp; <select name="statusType">
						          <?php 
							        foreach($data['statusTypes'] as $item)
							        {
							        	$val = $item['id'];
							        	$text = $item['name'];
							        	echo '<option value="'.$val.'" '.($val == 5 ? 'selected="selected"': '').'>'.$text.'</option>';		        	
							        }?>	          
						          </select></div>
						<div><button id="createOrder" class="btn btn-primary btn-large" type="button">Crear (2) Ordenes</button></div>
						</td>
					</tr>
				</tbody>
			</table>
	    </form>
	    <select name="spareShippingList" id="spareShippingList" style="display:none;">
						          <?php 
							        foreach($data['containerTypes'] as $item)
							        {
							        	$val = $item['id'];
							        	$text = $item['name'];
							        	echo '<option value="'.$val.'">'.$text.'</option>';		        	
							        }?>	          
						          </select>
		<label id="result"></label>
