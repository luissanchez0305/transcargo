<?php
		        echo '<table class="table table-hover table-size">';
		        echo '<tr>';
		        echo '<th># Orden</th>';
		        echo '<th>Fecha del viaje</th>';
		        echo '<th>Cliente</th>';
		        echo '<th>Tarifa cliente</th>';
		        echo '<th>Conductor</th>';
				echo '<th>Tarifa conductor</th>';
		        echo '<th>M. Vinculados</th>';
				echo '</tr>';		        
		        foreach($data['list'] as $item)
		        {
		        	echo '<tr class="editDialog hand">';
		        	echo '<td><a href="#">'.$item['code'].'</a><input type="hidden" value="'.$item['itemId'].'"/></td>';
		        	echo '<td>'.$item['tripDate'].'</td>';
		        	echo '<td>'.$item['client'].'</td>';
		        	echo '<td>$ '.number_format($item['clientRate'],2).'</td>';
		        	echo '<td>'.$item['driver'].'</td>';
		        	echo '<td>$ '.number_format($item['driverRate'],2).'</td>';
		        	echo '<td>&nbsp;</td>';
					echo '</tr>';
		        }
		        echo '</table>';
?>