<?php
class Input_Model {
	
    /**
     * Holds instance of database connection
     */
    private $db;
    private $queries;
	
    public function __construct()
    {
        $this->db = new MysqlImproved_Driver;
        $this->queries = new Queries_Extension;
    }
    
    public function insert_item($moveType, $date, $client, $from, $to, $infoType1, $infoType2, $infoType3, 
    	$containerBL, $shipping, $comment, $statusType, $containerCodes, $containerTypes, $isFulls)
    {  
    	//connect to database
        $this->db->connect();
        
        $placeQuery = $this->queries->places_query;
        $this->db->prepare($placeQuery.' WHERE p.id = '.$from);
        $this->db->query();
        $fromPlace = $this->db->fetchRow('array');
        $this->db->prepare($placeQuery.' WHERE p.id = '.$to);
        $this->db->query();
        $toPlace = $this->db->fetchRow('array');
        
	    $dateConverted = date("Y-m-d",strtotime($date));
	    $dateCreated = date("Y-m-d H:i:s");
        // De puerto a bodega
        $letter = '';
        if($toPlace[1] == '1'){    
        	$letter = 'A';
	        $query = "INSERT INTO orders (tripDate, clientId, fromPlaceId, toPlaceId, shippingCo, comment, createdDate, ".
	        "containerBL, movementType) VALUES ('$dateConverted', '$client', '$from', '$to', '$shipping', '$comment',".
	        "'$dateCreated', '$containerBL', '$moveType')";
	        $this->db->prepare($query);   
	        
	        //execute query
	        $this->db->query();
        	$inserted_id = $this->db->getLastId();
        	
        	// inserta contenedores o furgon a order items
        	$ind = 0;
        	if($moveType == 1) {	
	        	$query = "INSERT INTO orderitems (status, orderId, infoType1, infoType2, infoType3, itemCode, isFull, ".
	        	"letter) VALUES ('$statusType', '$inserted_id', $infoType1, ".
	        	"$infoType2, $infoType3,'$containerCodes[0]', 1,'$letter')";
	        	
	        	$this->db->prepare($query); 
	        	  
		        //execute query
		        $this->db->query();
		        
		        $lastId = $this->db->getLastId();
        		$code = str_pad($lastId,5,"0",STR_PAD_LEFT);
        		$query = "UPDATE orderitems SET code = '".$code."' WHERE id = $lastId"; 
	        		
		        $this->db->prepare($query);  
		        
			    //execute query
			    $this->db->query();
        	}
	        else {
        		foreach($containerCodes as $containerCode){ 
	        		$containerType = $containerTypes[$ind];
	        		$isFull = $isFulls[$ind];
	        		$query = "INSERT INTO orderitems (status, orderId, infoType1, infoType2, infoType3, itemCode, ".
	        		"containerType, isFull, letter) VALUES ('$statusType', '".
	        		"$inserted_id', $infoType1, $infoType2, $infoType3, '$containerCode', '$containerType', $isFull, ".
	        		"'$letter')";
	        		
		        	$this->db->prepare($query);
		        	
			        //execute query
			        $this->db->query();
			        
		        	$ind += 1; 	
		        	
			        $lastId = $this->db->getLastId();
	        		$code = str_pad($lastId,5,"0",STR_PAD_LEFT);
	        		$query = "UPDATE orderitems SET code = '".$code."' WHERE id = $lastId"; 
	        		
		        	$this->db->prepare($query); 
		        	  
			        //execute query
			        $this->db->query();
	        	}
	        	
        	}
        	        	
        	// retorno de los contenedores
        	if($moveType == 2 && $fromPlace[1] == '2'){
        		$letter = 'X';
	        	$query = "INSERT INTO orders (tripDate, clientId, fromPlaceId, toPlaceId, containerBL, ".
		        "shippingCo, comment, createdDate, movementType) VALUES ('$dateConverted', '$client', '$from', '$to', ".
		        "'$containerBL', '$shipping', '$comment', '$dateCreated', '$moveType')";		        
	        	$this->db->prepare($query);  
	        	
		        //execute query
		        $this->db->query();
        		$inserted_id = $this->db->getLastId();
        	
	        	// inserta contenedores a order items
	        	$ind = 0;
        		foreach($containerCodes as $containerCode){ 
	        		$containerType = $containerTypes[$ind];
	        		$isFull = $isFulls[$ind];
	        		$query = "INSERT INTO orderitems (status, orderId, infoType1, infoType2, infoType3, itemCode, containerType, isFull, letter) VALUES ('$statusType', '".
	        		"$inserted_id', $infoType1, $infoType2, $infoType3, '$containerCode', '$containerType', 0, '$letter')";
	        		$ind += 1; 	
		        	$this->db->prepare($query); 
		        	
			        //execute query
			        $this->db->query();			        
		        	
			        $lastId = $this->db->getLastId();
	        		$code = str_pad($lastId,5,"0",STR_PAD_LEFT);
	        		$query = "UPDATE orderitems SET code = '".$code."' WHERE id = $lastId"; 
	        		
		        	$this->db->prepare($query);  
		        	
			        //execute query
			        $this->db->query();			        
	        	}
        	}
        }
        else if($toPlace[1] == '2'){
        	$letter = 'X';
	        $query = "INSERT INTO orders (tripDate, clientId, fromPlaceId, toPlaceId, containerBL, ".
	        "shippingCo, comment, createdDate, movementType) VALUES ('$dateConverted', '$client', '$from', '$to', ".
	        "'$containerBL', '$shipping', '$comment', '$dateCreated', '$moveType')";
	        $this->db->prepare($query);
	        
	        //execute query
	        $this->db->query();
	        
        	$inserted_id = $this->db->getLastId();  
        
	        // inserta contenedores a order items
	        $ind = 0;
        	if($moveType == 1) {      	
	        	$query = "INSERT INTO orderitems (status, orderId, infoType1, infoType2, infoType3, itemCode, isFull, letter) VALUES ('$statusType', '".
	        	"$inserted_id', $infoType1, $infoType2, $infoType3, '$containerCodes[0]', 1,'$letter')";
		        $this->db->prepare($query);     
		        
			    //execute query
			    $this->db->query();
			    
			    $lastId = $this->db->getLastId();
	        	$code = str_pad($lastId,5,"0",STR_PAD_LEFT);
	        	$query = "UPDATE orderitems SET code = '".$code."' WHERE id = $lastId"; 
	        		
		        $this->db->prepare($query); 
		        
			    //execute query
			    $this->db->query();	
			    
        	}
	        else {
        		foreach($containerCodes as $containerCode){ 
			       	$containerType = $containerTypes[$ind];
			       	$isFull = $isFulls[$ind];
			       	$query = "INSERT INTO orderitems (status, orderId, infoType1, infoType2, infoType3, itemCode, containerType, isFull, letter) VALUES ('$statusType', '".
			       	"$inserted_id', $infoType1, $infoType2, $infoType3, '$containerCode', '$containerType', $isFull, '$letter')";
			       	$ind += 1;
				    $this->db->prepare($query);  
				    
					//execute query
					$this->db->query();
					
				    $lastId = $this->db->getLastId();
		        	$code = str_pad($lastId,5,"0",STR_PAD_LEFT);
		        	$query = "UPDATE orderitems SET code = '".$code."' WHERE id = $lastId"; 
		        		
			        $this->db->prepare($query);   
			        
				    //execute query
				    $this->db->query();	
				    
		        } 	
	        }
        }
        
        return TRUE;
    }

    public function update_order_item($order, $client, $date, $moveType, $from,	$to, $infoType1, $infoType2, 
    	$infoType3, $containerBL, $shipping, $comment, $statusType, $containerNumber, $containerType)
    {
    	//connect to database
        $this->db->connect();
        
    	$orderMasterId = $order['id'];
    	$orderItemId = $order['orderItemId'];
    	    	
    	// hacer el query de update
    	$query = "UPDATE orders SET tripDate = '".date("Y-m-d",strtotime($date))."', clientId = '$client', ".
    	"fromPlaceId = '$from', toPlaceId = '$to', fromPlaceId = '$from', movementType = '$moveType', ".
    	"containerBL = '$containerBL', shippingCo = '$shipping', comment='$comment' ".
    	"WHERE id = '$orderMasterId'";
    	
        $this->db->prepare($query);
        $this->db->query();
        
        // hacer query de update para orderitem
        $isFull = $order['isFull'];
        if(strlen($infoType1) > 0 && ($infoType1 == 1 || $infoType1 == 4) && ($order['letter'] == 'X' || $order['letter'] == 'Y' || $order['letter'] == 'Z'))
        	$isFull = 0;
    	$query = "UPDATE orderitems SET status = '$statusType', containerType = '$containerType', ".
    	"infoType1 = $infoType1, infoType2 = $infoType2, infoType3 = $infoType3, itemCode = '".
    	"$containerNumber', isFull = $isFull WHERE id = '$orderItemId'";    	
    	
        $this->db->prepare($query);
        $this->db->query();
        
        return true;
    }
    
    public function update_allocation_item($order, $transport, $driver, $chassis, $isFull, $activity, $comment)
    {
    	//connect to database
        $this->db->connect();
        
    	$orderItemId = $order['orderItemId'];
    	
    	// hacer la comparacion de conductor, si cambio crear una orden nueva
    	if(strlen($order['driverId']) > 0 && $order['driverId'] != $driver)
    	{
    		// crear orden nueva
    		$orderMasterId = $order['id'];
    		$containerNumber = $order['containerNumber'];
    		$containerType = $order['containerType'];
    		$infoType1 = $order['infoType1'];
    		$infoType2 = $order['infoType2'];
    		$infoType3 = $order['infoType3'];
    		$originalOrderCode = $order['code'];
    		$nextLetter = $this->calculateNextLetter($order['letter']);
    		$originalStatus = $order['status'];
    		
    		if(strlen($infoType1)>0)
    			$infoType1 = "'$infoType1'";
    		else
    			$infoType1 = "null";    		
    		if(strlen($infoType2)>0)
    			$infoType2 = "'$infoType2'";
    		else
    			$infoType2 = "null";    		
    		if(strlen($infoType3)>0)
    			$infoType3 = "'$infoType3'";
    		else
    			$infoType3 = "null";
    		
    		$query = "INSERT INTO orderitems (orderId, itemCode, containerType, isFull, chassis, vehicleId, orderItemLinked, code, letter, status, driverId) VALUES ".
    		"('$orderMasterId', '$containerNumber', '$containerType', $isFull, '".
    		"$chassis', '$transport', '$orderItemId', '$originalOrderCode', ".
    		"'$nextLetter', '$originalStatus','$driver')";
    		
    		$this->db->prepare($query);    		
	        $this->db->query();
	        
			if($activity == 1 || $activity == 5 || $activity == 9)
			{
				$orderItemId = $this->db->getLastId();
			}
	            		
    		// cambiar el driver del vehicleid de la orden
    		$query = "UPDATE vehicles SET driverId = '$driver' WHERE id = $transport";
    		
    		$this->db->prepare($query);
	        $this->db->query();
	            		
    		//cancelar la orden anterior
    		$query = "UPDATE orderitems SET status = (SELECT id FROM statustypes WHERE name LIKE 'Cancelado') WHERE id = ".$orderItemId;
    		
    		$this->db->prepare($query);
	        $this->db->query();    		
    		return true;
    	}
    	else    	
    	{
    		$query = "UPDATE orderitems SET driverId = '$driver', status = (SELECT id FROM statustypes WHERE name LIKE '%Ejecucion') WHERE id = $orderItemId";
    		$this->db->prepare($query);
	        $this->db->query();     		
    	}
    	
    	// insertar activity en caso que $activity sea mayor a -1
    	if($activity > -1)
    	{
	    	$dateCreated = date("Y-m-d H:i:s");
    		$query = "INSERT INTO orderactivities (dateCreated, activityId, comment, orderItemId) VALUES ('$dateCreated', '$activity', '$comment', '$orderItemId')";
				
	        $this->db->prepare($query);
	        $this->db->query();

	        if($activity == 1 || $activity == 5 || $activity == 9)
	        {
	    		//en ejecucion la orden nueva
	    		$query = "UPDATE orderitems SET status = (SELECT id FROM statustypes WHERE name LIKE 'En Ejecucion') WHERE id = ".$orderItemId;
	    		
	    		$this->db->prepare($query);
		        $this->db->query();    	
	        }
	        else if($activity == 13 || $activity == 14)
	        {
	    		//completada la orden nueva
	    		$query = "UPDATE orderitems SET status = (SELECT id FROM statustypes WHERE name LIKE 'Completo') WHERE id = ".$orderItemId;
	    		$this->db->prepare($query);
		        $this->db->query();   	        	
	        }
    	}
    	// hacer el query de update
    	$query = "UPDATE orderitems SET isFull = $isFull, chassis = '$chassis', vehicleId = '$transport' WHERE id = $orderItemId";
    	
    	$this->db->prepare($query);
	    $this->db->query();      	
    	return true;
    }
    
    private function calculateNextLetter($letter)
    {
    	switch($letter)
    	{
    		case 'A':
    			return 'B';
    			break;
    		case 'B':
    			return 'C';
    			break;
    		case 'C':
    			return 'D';
    			break;
    		case 'D':
    			return 'E';
    			break;
    		case 'X':
    			return 'Y';
    			break;
    		case 'Y':
    			return 'Z';
    			break;
    		case 'Z':
    			return 'Z1';
    			break;
    		case 'Z1':
    			return 'Z2';
    			break;	    		
    	}
    	
    	return '';
    }
}