<?php

class Edit_Controller 
{
    public $template = 'edit';

    public function main(array $getVars)
    {
		$editModel = new Edit_Model;
		$order = $editModel->get_order($getVars['id']);
		
		$editViewModel = new View_Model('item');
		
		$editViewModel->assign('orderItemId', $order['orderItemId']);
		$editViewModel->assign('tripDate', $order['tripDate']);
		$editViewModel->assign('code', $order['code']);
		$editViewModel->assign('letter', $order['letter']);
		$editViewModel->assign('isContainerMovement', $order['movementType'] == '2');
		
		$editViewModel->assign('places', $editViewModel->get_places());
		$editViewModel->assign('fromPlaceId', $order['fromPlaceId']);
		$editViewModel->assign('toPlaceId', $order['toPlaceId']);		
		
	    $editViewModel->assign('clients', $editViewModel->get_general_list('clients'));
	    $editViewModel->assign('clientId', $order['clientId']);
	    
	    $editViewModel->assign('moveTypes',$editViewModel->get_general_list('moveTypes'));
	    $editViewModel->assign('movementType', $order['movementType']);
	    
	    $editViewModel->assign('autoComments',$editViewModel->get_infoTypes());
	    $editViewModel->assign('autoComment1', $order['infoType1']);
	    $editViewModel->assign('autoComment2', $order['infoType2']);
	    $editViewModel->assign('autoComment3', $order['infoType3']);
	    $editViewModel->assign('containerTypes',$editViewModel->get_general_list('containers'));
	    $editViewModel->assign('containerType', $order['containerType']);
	    
	    $editViewModel->assign('shippings', $editViewModel->get_general_list('shippings'));
	    $editViewModel->assign('shippingCo', $order['shippingCo']);
	    $editViewModel->assign('containerBL', $order['containerBL']);
	    $editViewModel->assign('containerNumber', $order['containerNumber']);
	    $editViewModel->assign('comment', $order['comment']);
	    
	    $editViewModel->assign('chassises', $editViewModel->get_general_list('chassises'));
	    $editViewModel->assign('chassisId', $order['chassisId']);
	    $editViewModel->assign('chassis', $order['chassis']);
	    $editViewModel->assign('isFull', $order['isFull']);
	    	    
	    $editViewModel->assign('statusTypes', $editViewModel->get_general_list('statusTypes'));
	    $editViewModel->assign('status', $order['status']);
	    
	    $editViewModel->assign('activities', $editModel->get_activities($order['fromPlaceType'], $order['toPlaceType']));
	    $editViewModel->assign('transports', $editModel->get_transports());
	    $editViewModel->assign('vehicleId', $order['vehicleId']);
	    $editViewModel->assign('drivers', $editViewModel->get_general_list('drivers'));
	    $editViewModel->assign('activityItems', $editModel->get_order_activities($order['orderItemId']));
	    $editViewModel->assign('driverId', $order['driverId']);
	    $editViewModel->assign('vehicleId', $order['vehicleId']);
	    
	    /* Si la orden tiene asignado un vehiculo que es de una compañia propia se cargan todos los drivers
	     * Si el vehiculo es de una compañia subcontratada se carga el conductor asignado a ese vehiculo
	     * if($order['vehicleId'] > 0)
	    {
	    	$vehicle = $editViewModel->get_vehicle($order['vehicleId']);
	    	
			$drivers = array();
			$value = array(
				'id' => $vehicle['driverId'],
				'name' => $vehicle['driverName']
			);
			array_push($drivers, $value);	
			$editViewModel->assign('drivers', $drivers);		
	    }
	    else	   
	    	$editViewModel->assign('drivers', $editViewModel->get_general_list('drivers'));
	    	*/
	    
    	echo $editViewModel->render(FALSE);
    }
}