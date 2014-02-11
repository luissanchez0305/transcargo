<?php

class Input_Controller 
{
    public function main(array $getVars)
    {    	
    	$result = TRUE;
	    $model = new Input_Model;
	    if($getVars['mode'] == 'insert') {
	    	$client = $getVars['client'];
	    	$date = $getVars['dateCreate'];
	    	$moveType = $getVars['moveType'];
	    	$from = $getVars['from'];
	    	$to = $getVars['to'];
	    	$infoType1 = strlen($getVars['autoComment1']) == 0 ? 'null' : $getVars['autoComment1'];
	    	$infoType2 = strlen($getVars['autoComment2']) == 0 ? 'null' : $getVars['autoComment2'];
	    	$infoType3 = strlen($getVars['autoComment3']) == 0 ? 'null' : $getVars['autoComment3'];
	    	$itemsToMove = $getVars['itemsToMove'];
	    	$containerBL = $getVars['containerBL'];
	    	$shipping = $getVars['shipping'];
	    	$comment = $getVars['comment'];
	    	$statusType = $getVars['statusType'];
	    	$containerCodes = array();
	    	$containerTypes = array();
	    	$isFulls = array();
	    	
	    	for($index = 0; $index < $itemsToMove; $index++)
	    	{
	    		$containerCode = $getVars['containerCode['.$index.']'];
	    		$containerType = $getVars['containerType['.$index.']'];
	    		$isFull = $getVars['isFull['.$index.']'];
				array_push($containerCodes, $containerCode);
				array_push($containerTypes, $containerType);
				array_push($isFulls, $isFull);
	    	}
	    	
	    	$result = $model->insert_item( 
	    	$moveType,
	    	$date, 
	    	$client, 
	    	$from, 
	    	$to,
	    	$infoType1,
	    	$infoType2,
	    	$infoType3,
	    	$containerBL,
	    	$shipping,
	    	$comment,
	    	$statusType,
	    	$containerCodes,
	    	$containerTypes,
	    	$isFulls);
	    }
	    else if($getVars['mode'] == 'editOrder') {	    
	    	$orderId = $getVars['orderItemId'];
	    	
	    	$editModel = new Edit_Model;
	    	$order = $editModel->get_order($orderId);
	    	
	    	$client = $getVars['client'];
	    	$date = $getVars['dateEdit'];
	    	$moveType = $getVars['editMoveType'];
	    	$from = $getVars['from'];
	    	$to = $getVars['to'];
	    	
	    	$infoType1 = $getVars['autoComment1'];
	    	$infoType2 = $getVars['autoComment2'];
	    	$infoType3 = $getVars['autoComment3'];	
	    	$containerBL = $getVars['containerBL'];
	    	$shipping = $getVars['shippingCo'];
	    	$comment = $getVars['comment'];
	    	$statusType = $getVars['statusType'];	
	    	$containerNumber = $getVars['containerNumber'];
	    	$containerType = $getVars['containerType'];	  
	    	  
	    	$result = $model->update_order_item($order, $client, $date, $moveType, $from, $to, $infoType1, $infoType2, $infoType3,
	    		$containerBL, $shipping, $comment, $statusType,	$containerNumber, $containerType);
	    }
	    else if($getVars['mode'] == 'editAlloc') {
	    	$orderId = $getVars['orderItemId'];
	    	
	    	$editModel = new Edit_Model;
	    	$order = $editModel->get_order($orderId);
	    	
	    	$transport = $getVars['transport'];
	    	$driver = $getVars['driver'];    
	    	$chassis = $getVars['chassis'];
	    	$isFull = $getVars['isFull'];
	    	$activity = $getVars['activity'];
	    	$comment = $getVars['comment'];	

	    	$result = $model->update_allocation_item($order, $transport, $driver, $chassis, $isFull, $activity,	$comment);
	    }
	    	
	    if($result)
	    	echo 'Guardado';
	    else
	    	echo 'Error';
	    	
    	/*if($result){
	    	$view->assign('success',TRUE);
    		$view->assign('result', 'La orden ha sido creada con exito');
    	}	
    	else {
	    	$view->assign('success',FALSE);
    		$view->assign('result', 'Ha sucedido un error');    		
    	}
    	$view->render();*/
    	
    }
}