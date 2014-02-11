		<script>
		    $(function() {
		        $('#dateEdit').datepicker();
		    });
		</script>		
		<form method="post" action="/?input" id="editOrderForm">
		  <div><h2>Detalles del movimiento - <?= $data['code']; ?>-<?= $data['letter']; ?></h2></div>
		  <input type="hidden" name="orderItemId" value="<?= $data['orderItemId']; ?>" />
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
				        	echo '<option value="'.$val.'"'.($val == $data['clientId'] ? ' selected="selected"' : '').'>'.$text.'</option>';			        	
				        }?>	          
			        </select>
				</dd></dl></div>
				</td>
				</tr>
				<tr>
				<td style="width: 195px"><div>Fecha Del Movimientos: <input type="text" id="dateEdit" name="dateEdit" size="20" style="width:100px;" value="<?= $data['tripDate']; ?>"></div>
					<div>Tipo de Movimiento: 
						<select id=editMoveType name="editMoveType" onchange="modifyContainerDisplay(this)">
			          		<?php 
				        	foreach($data['moveTypes'] as $item)
				        	{
					        	$val = $item['id'];
					        	$text = $item['name'];
					        	echo '<option value="'.$val.'"'.($val == $data['movementType'] ? ' selected="selected"' : '').'>'.$text.'</option>';			        	
				        	}?>					
						</select>
					</div>
				</td>
				<td style="width: 235px">
					<div>Origen: 
						<select id="from" name="from">
			          		<?php 
				        	foreach($data['places'] as $item)
				        	{
				        		$type = $item['type'];
					        	$val = $item['id'];
					        	$text = $item['name'];
					        	echo '<option value="'.$val.'"'.($val == $data['fromPlaceId'] ? ' selected="selected"' : '').'>'.$text.' - '.$type.'</option>';			        	
				        	}?>
			          	</select>
			        </div>
				<div>Destino: <select id="to" name="to">
			          		<?php 
				        	foreach($data['places'] as $item)
				        	{
				        		$type = $item['type'];
					        	$val = $item['id'];
					        	$text = $item['name'];
					        	echo '<option value="'.$val.'"'.($val == $data['toPlaceId'] ? ' selected="selected"' : '').'>'.$text.' - '.$type.'</option>';			        	
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
								if(!$isOpened && $ind < 2){
									$isOpened = TRUE;
									echo '<td style=" width: 229px;">';
								}
								
								if(!$isOpened && $ind >= 2 && $ind < 4){
									$isOpened = TRUE;
									echo '<td style=" width: 258px;">';									
								}
								
								if(!$isOpened && $ind >= 4){
									$isOpened = TRUE;	
									echo '<td>';										
								}
								
								echo '<div><label class="radio"><input type="radio" name="autoComment'.$section.'" id="autoComment'.$section.'" value="'.$val.'"'.($val == $data['autoComment1'] || $val == $data['autoComment2'] || $val == $data['autoComment3'] ? ' checked="checked"' : '').' />'.$text.'</label></div>';
								
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
						<td style="width: 195px">
							<div <?php if($data['isContainerMovement']) echo ''; else echo 'class="hide"'; ?>> 
							<label style="float:left;">B/L:</label>
							<input type="text" name="containerBL" value="<?= $data['containerBL']; ?>"></input>
							</div>
							<div <?php if($data['isContainerMovement']) echo ''; else echo 'class="hide"'; ?>> 
								<label style="float:left;">Naviera:</label>
								<select name="shippingCo">
					          		<?php 
						        	foreach($data['shippings'] as $item)
						        	{
							        	$val = $item['id'];
							        	$text = $item['name'];
							        	echo '<option value="'.$val.'"'.($val == $data['shippingCo'] ? ' selected="selected"' : '').'>'.$text.'</option>';			        	
						        	}?>
					          	</select>
							</div>
							<div> 
								<label style="float:left;"><?php if($data['isContainerMovement']) echo 'Contenedor'; else echo 'Furgon'; ?>:</label>
								<input type="text" name="containerNumber" value="<?= $data['containerNumber']; ?>" />
							</div>	
							<div style="width: 220px;" <?php if($data['isContainerMovement']) echo ''; else echo 'class="hide"'; ?>>	 
								<label style="float:left;">Tipo:</label>
								<select name="containerType" style="float:left; width: 122px;">
					          		<?php 
						        	foreach($data['containerTypes'] as $item)
						        	{
							        	$val = $item['id'];
							        	$text = $item['name'];
							        	echo '<option value="'.$val.'"'.($val == $data['containerType'] ? ' selected="selected"' : '').'>'.$text.'</option>';			        	
						        	}?>
					          	</select>
							</div>
							<div></div>						
						</td>
					<td style="width: 235px">
						<div>
							Estado:
						</div>
						<div>
							<select name="statusType">
				          		<?php 
					        	foreach($data['statusTypes'] as $item)
					        	{
						        	$val = $item['id'];
						        	$text = $item['name'];
						        	echo '<option value="'.$val.'"'.($val == $data['status'] ? ' selected="selected"' : '').'>'.$text.'</option>';			        	
					        	}?>
				          	</select>					
						</div>
						<div>
							Comentarios:&nbsp;
							<textarea name="comment" style="width: 325px; height: 70px;"><?= $data['comment']; ?></textarea>
						</div>
						<div>
							<button id="btnEditOrder" class="btn btn-success" type="button">Guardar</button>
							<label id="orderResult"></label>
						</div>
					</td>
					</tr>
				</tbody>
			</table>
			</form>			
			<form method="post" action="/?input" id="editAllocationForm">
		  	<input type="hidden" name="orderItemId" value="<?= $data['orderItemId']; ?>" />
			<div><h2>Asignacion</h2></div>
			<table class="table table table-size">
				<tbody>
					<tr>
						<td style="width: 195px">
							<div>Cod. de Transporte: 
								 <select name="transport">
						         	<?php 
						         	if('' == $data['vehicleId'])
						         	{
						         		echo '<option value=" selected="selected">Escoja uno...</option>';
						         	}
							        foreach($data['transports'] as $item)
							        {
								       	$val = $item['id'];
								       	$text = $item['providerName'].'-'.$item['code'];
								       	echo '<option value="'.$val.'"'.($val == $data['vehicleId'] ? ' selected="selected"' : '').'>'.$text.'</option>';			        	
							        }?>
						         </select>	
					         </div>
							<div>Conductor: 
								 <select name="driver">
						         	<?php 
						         	if('' == $data['driverId'])
						         	{
						         		echo '<option value=" selected="selected">Escoja uno...</option>';
						         	}
							        foreach($data['drivers'] as $item)
							        {
								       	$val = $item['id'];
								       	$text = $item['name'];
								       	echo '<option value="'.$val.'"'.($val == $data['driverId'] ? ' selected="selected"' : '').'>'.$text.'</option>';			        	
							        }?>
						         </select>	
					        </div>				        
							<div><?php if($data['isContainerMovement']) echo 'Chasis'; else echo 'Furgón'; ?>: 
								<input name="chassis" type="text" value="<?= $data['chassis']; ?>">
							</div>
							<div>
								<label class="radio" style="float:left;">
									<input type="radio" name="isFull" id="isFull" value="1" <?= $data['isFull'] == 1 ? 'checked="checked"' : ''; ?> /> 
									Lleno</label>
								<label class="radio" style="float:left;padding-left:40px;">
									<input type="radio" name="isFull" id="isFull" value="0" <?= $data['isFull'] == 0 ? 'checked="checked"' : ''; ?> />
									Vacio</label>
							</div>
							<div>&nbsp;</div>
							<div>
								<label class="checkbox inline">
									<input type="checkbox" id="inlineCheckbox1" value="option1"> Polleras
								</label>
								<label class="checkbox inline">
									<input type="checkbox" id="inlineCheckbox2" value="option2"> Placas
								</label>
							</div>
							<div>
								<label class="checkbox inline">
								<input type="checkbox" id="inlineCheckbox1" value="option1"> Luces</label>
								<label class="checkbox inline">
								<input type="checkbox" id="inlineCheckbox2" value="option2"> Patines
								</label>
							</div>
							<div>
								<label class="checkbox inline">
									<input type="checkbox" id="inlineCheckbox1" value="option1"> Cables
								</label>
								<label class="checkbox inline">
									<input type="checkbox" id="inlineCheckbox2" value="option2"> Frenos
								</label>
							</div>						
						</td>
						<td style="width: 235px">
							<div>Actividad:
								<select name="activity">
									<option value="-1">Escoja una...</option>
					          		<?php 
						        	foreach($data['activities'] as $item)
						        	{
							        	$val = $item['id'];
							        	$text = $item['name'];
							        	echo '<option value="'.$val.'">'.$text.'</option>';			        	
						        	}?>
					          	</select>	
				          	</div>
							<div>Comentarios:&nbsp;
								<textarea name="comment" style="width: 325px; height: 70px"></textarea>
							</div>
							<div>
								<button id="btnEditAllocation" class="btn btn-success" type="button">Guardar</button>
								<label id="allocationResult"></label>
							</div>
						</td>
					</tr>
				</tbody>
			</table>						
			<h4>Historial</h4>
			<table class="table table-hover table-size">
				<thead>
					<tr>
						<th style="width: 60px">Hora</th>
						<th style="width: 84px">Fecha</th>
						<th style="width: 205px">Actividad</th>
						<th style="width: 266px">Comentario</th>
						<th>Informar Cliente</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($data['activityItems'] as $item)
					{ 
						echo '<tr>'.
						'<td style="width: 60px">'.date("H:i", strtotime($item['dateCreated'])).'</td>'.
						'<td style="width: 84px">'.date("m/d/Y", strtotime($item['dateCreated'])).'</td>'.
						'<td style="width: 205px">'.$item['activityName'].'</td>'.
						'<td style="width: 266px">'.$item['comment'].'</td>'.
						'<td><button class="btn btn-danger hide" type="button">Enviar</button></td>'.
						'</tr>';						
					}?>
				</tbody>
			</table>
	    </form>