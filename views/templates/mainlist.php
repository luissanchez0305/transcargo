<?php
echo '<table class="table table-hover table-size">';
		        echo '<tr>';
		        echo '<th># Orden</th>';
		        echo '<th>Estado</th>';
		        echo '<th>Fecha del viaje</th>';
		        echo '<th>Cliente</th>';
				echo '<th>Origen</th>';
		        echo '<th>Destino</th>';
		        echo '<th>M. Vinculados</th>';
				echo '</tr>';		        
		        foreach($data['list'] as $item)
		        {
		        	$statusClass = 'info';
		        	switch($item['statusId'])
		        	{
		        		case 2:
		        			$statusClass = 'important';
		        			break;
		        		case 1:
		        			$statusClass = 'warning';
		        			break;		  
		        		case 4:
		        			$statusClass = 'success';      		
		        	}
		        	echo '<tr class="editDialog hand">';
		        	echo '<td><a href="#">'.$item['code'].'</a><input type="hidden" value="'.$item['itemId'].'"/></td>';
		        	echo '<td><span class="label label-'.$statusClass.'">'.$item['status'].'</td>';
		        	echo '<td>'.$item['tripDate'].'</td>';
		        	echo '<td>'.$item['client'].'</td>';
		        	echo '<td>'.$item['fromPlace'].'</td>';
		        	echo '<td>'.$item['toPlace'].'</td>';
		        	echo '<td>&nbsp;</td>';
					echo '</tr>';
		        }
		        echo '</table>';
?>