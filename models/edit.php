<?php
class Edit_Model {
	
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
    
    public function get_vehicles()
    {
        //connect to database
        $this->db->connect();
        
        //prepare query
        $this->db->prepare
        (
            $this->queries->places_query
        );
						
        //execute query
        $this->db->query();
        
		$items = array();
		while($row = $this->db->fetchRows()) {
			$value = array
			(
				'id' => $row['id'],
				'type' => $row['type'],
				'name' => $row['name']
			);
						
			array_push($items, $value);
		}	
		return $items;    	
    }
    
    public function get_activities($from, $to)
    {
        //connect to database
        $this->db->connect();
        
        //prepare query
        $this->db->prepare
        (
            sprintf($this->queries->activities_query, $from, $to)
        );
        //execute query
        $this->db->query();
        
		$items = array();
		while($row = $this->db->fetchRows()) {
			$value = array
			(
				'id' => $row['id'],
				'name' => $row['name']
			);
						
			array_push($items, $value);
		}	
		return $items;        	
    }
    
    public function get_transports()
    {
        //connect to database
        $this->db->connect();
        
        //prepare query
        $this->db->prepare
        (
            $this->queries->transports_query.' ORDER BY isSubcontract DESC'
        );
		
        //execute query
        $this->db->query();
        
		$items = array();
		while($row = $this->db->fetchRows()) {
			$value = array
			(
				'id' => $row['id'],
				'code' => $row['code'],
				'driverId' => $row['driverId'],
				'driverName' => $row['driverName'],
				'providerId' => $row['providerId'],
				'providerName' => $row['providerName'],
				'isSubcontract' => $row['isSubcontract']		
			);
						
			array_push($items, $value);
		}	
		return $items;       	
    }
    
    public function get_order_activities($id)
    {
    	$this->db->connect();
    	$this->db->prepare(
    		sprintf($this->queries->order_activities_query,$id)
    	);
    	
    	$this->db->query();
    	
    	$items = array();
    	while($row = $this->db->fetchRows()) {
			$value = array
			(
				'id' => $row['id'],
				'activityName' => $row['activityName'],
				'fromPlaceName' => $row['fromPlaceName'],
				'toPlaceName' => $row['toPlaceName'],
				'comment' => $row['comment'],
				'dateCreated' => $row['dateCreated']		
			);
						
			array_push($items, $value);
		}    	
		return $items;
    }

    public function get_order($id)
    {  	
        //connect to database
        $this->db->connect();
        
	    //sanitize data
	    //$id = $this->db->escape($id);

        //prepare query
        $this->db->prepare
        (
            sprintf($this->queries->order_item_query,$id)
        );
        //execute query
        $this->db->query();
        
        $orderItem = $this->db->fetchRow('array');	
        
        //prepare query
        $this->db->prepare
        (
            sprintf($this->queries->order_query,$orderItem[1])
        );	
        //execute query
        $this->db->query();
        $row = $this->db->fetchRow('array');	
		return array
				(
					'id' => $row[0],
					'orderItemId' => $orderItem[0],
					'letter' => $orderItem[11],
					'code' => $orderItem[10],
					'status' => $orderItem[12],
					'tripDate' => date("m/d/Y", strtotime($row[2])),
					'clientId' => $row[3],
					'fromPlaceId' => $row[4],
					'fromPlace' => $row[22],
					'fromPlaceType' => $row[23],
					'toPlaceId' => $row[5],
					'toPlace' => $row[24],
					'toPlaceType' => $row[25],
					'movementType' => $row[8],
					'movementName' => $row[27],	
					'infoType1' => $orderItem[5],
					'infoType2' => $orderItem[14],
					'infoType3' => $orderItem[15],
					'infoTypeName1' => $orderItem[19],
					'infoTypeName2' => $orderItem[20],
					'infoTypeName3' => $orderItem[21],
					'shippingCo' => $row[10],
					'shippingName' => $row[31],
					'containerBL' => $row[11],
					'containerNumber' => $orderItem[2],
					'containerName' => $orderItem[14],
					'chassis' => $orderItem[7],
					'chassisId' => $orderItem[6],
					'containerType' => $orderItem[3],
					'isFull' => $orderItem[4],
					'comment' => $row[15],
					'vehicleId' => $orderItem[8],
					'clientName' => $row[21],
					'driverId' => $orderItem[13],
					'driverName' => $orderItem[16]					
				);
				
    }
}