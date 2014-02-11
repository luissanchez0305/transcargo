<?php
class List_Model {
	
    /**
     * Holds instance of database connection
     */
    private $db;
    private $queries;
    
    public function __construct()
    {
    	$this->queries = new Queries_Extension;
        $this->db = new MysqlImproved_Driver;
    }
    
    public function get_mng_list($searchOption, $searchParam)
    {
    	$list_query_modified = $this->queries->list_query . " WHERE oi.status = 7";
    	if(strlen($searchOption) > 0)
    	{
    		if($searchOption == 'bl')
    			$list_query_modified = $list_query_modified . " AND o.containerBL LIKE '".$searchParam."'";
    		else if($searchOpotion == 'order')
    		{
        		$searchParamArray = explode('-', $searchParam);
        		$list_query_modified = $list_query_modified . " AND oi.id = '".intval($searchParamArray[0])."'";
        		if(strlen($searchParamArray[1]) > 0)
        			$list_query_modified = $list_query_modified . " AND LOWER(oi.letter) = '".strtolower($searchParamArray[1])."'";				    				
    		}
        	else if($searchOption == 'container')
        	{
        		$list_query_modified = $list_query_modified . " AND oi.itemCode = '".$searchParam."'";
        	}
        	else if(strpos($searchOption,'advance') !== false)
        	{
        		$sOptions = explode(',', $searchOption);
        		$sParams = explode(',', $searchParam);
        		$paramIndex = 0;
        		foreach($sParams as $param){
        			switch($param){
        				case 'date':
							list($year,$month,$day) = explode("-",$sOptions[$paramIndex + 1]);
							$month++;
							$day = min($day,date("t",strtotime($year."-".$month."-01"))); 
							$nextMonth = mktime(0,0,0,$month,$day,$year);
							$dateTop = strtotime("-1 days",$nextMonth);
								
	        				$list_query_modified = $list_query_modified . " AND o.tripDate >= '" . $sOptions[$paramIndex + 1] . "' AND o.tripDate <= '" . date('Y-m-d',$dateTop) . "'";
        					break;
        				case 'client':
		       				$list_query_modified = $list_query_modified . " AND o.clientId = '" . $sOptions[$paramIndex + 1] . "'";		
        					break;
        				case 'driver':
		       				$list_query_modified = $list_query_modified . " AND o.driverId = '" . $sOptions[$paramIndex + 1] . "'";
        					break;
        				case 'container':
		       				$list_query_modified = $list_query_modified . " AND oi.itemCode LIKE '" . $sOptions[$paramIndex + 1] . "'";
        					break;
        			}        			
        			$paramIndex++; 		
        		}
        	}
    	}
    	
    	$primary_list = $this->get_final_list($list_query_modified);    	
    	
		$items = array();
		$clientRateQueries = array();
		$driverRateQueries = array();
		foreach($primary_list as $item){
			$toPlaceId = $item['toPlaceId'];
	        // GET CLIENT RATE
	        
	        $this->db->prepare
	        (
	            "SELECT rate FROM clientrates WHERE clientId = '".$item['clientId']."' AND placeId = '$toPlaceId'"
	        );
							
	        //execute query
	        $this->db->query();
	        
	        $row = $this->db->fetchRow('array');	
	        $clientRate = $row[0];	        
	        
	        // GET DRIVER RATE
	        $driverId = $item['driverId'];
	        $this->db->prepare
	        (
	            "SELECT rate FROM driverrates WHERE driverId = '$driverId' AND placeId = '$toPlaceId'"
	        );
							
	        //execute query
	        $this->db->query();
	        
	        $row = $this->db->fetchRow('array');	
	        $driverRate = $row[0];
	        
			$value = array
			(
				'id' => $item['id'],
				'code' => str_pad($item['code'],5,"0",STR_PAD_LEFT),
				'status' => $item['statusName'],
				'tripDate' => $item['tripDate'],
				'client' => $item['client'],
				'clientRate' => $clientRate,
				'fromPlace' => $item['fromPlace'],
				'toPlace' => $item['toPlace'],
				'driver' => $item['driver'],
				'driverRate' => $driverRate
			);
						
			array_push($items, $value);
		}	
    	return $items; 
    }
    
    public function get_prg_list($option, $searchOption, $searchParam, $filterParams)
    {
        $list_query_modified = '';
        $dateRange = $this->calculate_daterange($option);
        if($option == 'yesterday')        
        	$list_query_modified = $this->queries->list_query . " WHERE o.tripDate = '" . date("Y-m-d",$dateRange["fromDate"]) . "' AND oi.status = 7";
        else if($option == 'today')
        	$list_query_modified = $this->queries->list_query . " WHERE o.tripDate <= '" . date("Y-m-d",$dateRange["fromDate"]) . "' AND oi.status <> 7";
        else if($option == 'tomorrow')
        	$list_query_modified = $this->queries->list_query . " WHERE o.tripDate = '" . date("Y-m-d",$dateRange["fromDate"]) . "' AND oi.status <> 7";
        else
        	$list_query_modified = $this->queries->list_query . " WHERE o.tripDate >= '" . date("Y-m-d",$dateRange["fromDate"]) . "' AND o.tripDate <= '" . date("Y-m-d",$dateRange["toDate"]) . "' AND oi.status <> 7";       
        
        $filterStatements = ' AND (';
        $filterIntroduced = FALSE;
        $putOrStatement = FALSE;
        if(strlen($filterParams) > 0) {        	
        	$filterParamsArray = explode(',', $filterParams);
        	foreach($filterParamsArray as $filterParam) {
	        	if($filterParam == 'empty') {
	        		if($putOrStatement)
	        			$filterStatements = $filterStatements . " OR";
	        		else
	        			$putOrStatement = TRUE;
	        		$filterIntroduced = TRUE;
	        		$filterStatements = $filterStatements . " oi.isFull = 0";
	        	}
	        	else if($filterParam == 'full') {
	        		if($putOrStatement)
	        			$filterStatements = $filterStatements . " OR";
	        		else
	        			$putOrStatement = TRUE;
	        		$filterIntroduced = TRUE;
	        		$filterStatements = $filterStatements . " oi.isFull = 1";
	        	}
        	}
        	if($filterIntroduced)
        		$filterStatements = $filterStatements . ') AND (';
        		
        	$putOrStatement = FALSE;
        	$filterIntroduced = FALSE;
        	foreach($filterParamsArray as $filterParam) {
	        	 if($filterParam == 'wDocument') {
	        		if($putOrStatement)
	        			$filterStatements = $filterStatements . " OR";
	        		else
	        			$putOrStatement = TRUE;
	        		$filterIntroduced = TRUE;
	        		$filterStatements = $filterStatements . " oi.status <> 2";
	        	}
	        	else if($filterParam == 'nDocument') {
	        		if($putOrStatement)
	        			$filterStatements = $filterStatements . " OR";
	        		else
	        			$putOrStatement = TRUE;
	        		$filterIntroduced = TRUE;
	        		$filterStatements = $filterStatements . " oi.status = 2";
	        	}
        	}
        	if($filterIntroduced)
        		$filterStatements = $filterStatements . ')';
        	else
        		$filterStatements = substr($filterStatements, 0, strlen($filterStatements) - 6);
        	$list_query_modified = $list_query_modified . $filterStatements;
        }
        
        if(strlen($searchOption) > 0)
        {
        	if($searchOption == 'bl')
        	{
        		$list_query_modified = $list_query_modified . " AND o.containerBL LIKE '".$searchParam."'"; 	
        	}
        	else if($searchOption == 'order')
        	{
        		$searchParamArray = explode('-', $searchParam);
        		$list_query_modified = $list_query_modified . " AND oi.id = '".intval($searchParamArray[0])."'";
        		if(strlen($searchParamArray[1]) > 0)
        			$list_query_modified = $list_query_modified . " AND LOWER(oi.letter) = '".strtolower($searchParamArray[1])."'";        		
        	}
        	else if($searchOption == 'container')
        	{
        		$list_query_modified = $list_query_modified . " AND oi.itemCode = '".$searchParam."'";
        	}
        	else if(strpos($searchOption,'advance') !== false)
        	{
        		$sOptions = explode(',', $searchOption);
        		$sParams = explode(',', $searchParam);
        		$paramIndex = 0;
        		foreach($sParams as $param){
        			switch($param){
        				case 'date':
							list($year,$month,$day) = explode("-",$sOptions[$paramIndex + 1]);
							$month++;
							$day = min($day,date("t",strtotime($year."-".$month."-01"))); 
							$nextMonth = mktime(0,0,0,$month,$day,$year);
							$dateTop = strtotime("-1 days",$nextMonth);
								
	        				$list_query_modified = $list_query_modified . " AND o.tripDate >= '" . $sOptions[$paramIndex + 1] . "' AND o.tripDate <= '" . date('Y-m-d',$dateTop) . "'";
        					break;
        				case 'client':
		       				$list_query_modified = $list_query_modified . " AND o.clientId = '" . $sOptions[$paramIndex + 1] . "'";		
        					break;
        				case 'driver':
		       				$list_query_modified = $list_query_modified . " AND o.driverId = '" . $sOptions[$paramIndex + 1] . "'";
        					break;
        				case 'container':
		       				$list_query_modified = $list_query_modified . " AND oi.itemCode LIKE '" . $sOptions[$paramIndex + 1] . "'";
        					break;
        			}        			
        			$paramIndex++; 		
        		}
        	}        	
        }
        return $this->get_final_list($list_query_modified);
    }
    
    public function calculate_daterange($option)
    {
    	$result = array();
    	
        if($option == 'yesterday') 
        	$result = array
			(
				'fromDate' => mktime(0,0,0,date('m'),date("d") - 1, date("Y")),
				'toDate' => mktime(0,0,0,1,1,2000)
			);
        else if($option == 'today'){
        	$result = array
			(
				'fromDate' => mktime(0,0,0,date('m'),date("d"), date("Y")),
				'toDate' => mktime(0,0,0,1,1,2000)
			);     
        }   	
        else if($option == 'tomorrow')
        	$result = array
			(
				'fromDate' => mktime(0,0,0,date('m'),date("d") + 1, date("Y")),
				'toDate' => mktime(0,0,0,1,1,2000)
			);        	
        else 
        {
        	$dayOfTheWeek = date('w');
        	$fromDate = mktime(0,0,0,date('m'),date("d") - $dayOfTheWeek, date("Y"));
        	$toDate =  mktime(0,0,0,date('m'),date("d") + (6 - $dayOfTheWeek), date("Y"));
        	$result = array
			(
				'fromDate' => $fromDate,
				'toDate' => $toDate
			);
        }
        return $result;
    }
    
    public function get_list($searchOption, $searchParam, $frame)
    {   
    	$modified_query = $this->queries->list_query;
    	if(strlen($searchOption) > 0){
    		if($searchOption == 'bl')
    			$modified_query = $modified_query . " WHERE o.containerBL LIKE '".$searchParam."'";
    		else if($searchOption == 'order')
    		{
        		$searchParamArray = explode('-', $searchParam);
        		$modified_query = $modified_query . " WHERE oi.id = ".intval($searchParamArray[0]);
        		if(strlen($searchParamArray[1]) > 0)
        			$modified_query = $modified_query . " AND LOWER(oi.letter) = '".strtolower($searchParamArray[1])."'";    			
    		}
        	else if($searchOption == 'container')
        	{
        		$modified_query = $modified_query . " WHERE LOWER(oi.itemCode) = '".strtolower($searchParam)."'";
        	}
        	else if(strpos($searchOption,'advance') !== false)
        	{
        		$sOptions = explode(',', $searchOption);
        		$sParams = explode(',', $searchParam);
        		$haveWhere = FALSE;
        		$haveStatement = FALSE;
        		$paramIndex = 0;
        		foreach($sParams as $param){
        			if(!$haveWhere){
        				$modified_query = $modified_query . " WHERE ";
        				$haveWhere = TRUE;
        			}
        			switch($param){
        				case 'date':
							list($year,$month,$day) = explode("-",$sOptions[$paramIndex + 1]);
							$month++;
							$day = min($day,date("t",strtotime($year."-".$month."-01"))); 
							$nextMonth = mktime(0,0,0,$month,$day,$year);
							$dateTop = strtotime("-1 days",$nextMonth);
							if($haveStatement)
	        					$modified_query = $modified_query . " AND ";
								
	        				$modified_query = $modified_query . " o.tripDate >= '" . $sOptions[$paramIndex + 1] . "' AND o.tripDate <= '" . date('Y-m-d',$dateTop) . "'";
	        				$haveStatement = TRUE;        					
        					break;
        				case 'client':
							if($haveStatement)
	        					$modified_query = $modified_query . " AND ";
		       				$modified_query = $modified_query . " o.clientId = '" . $sOptions[$paramIndex + 1] . "'";
	        				$haveStatement = TRUE;        					
        					break;
        				case 'driver':
							if($haveStatement)
	        					$modified_query = $modified_query . " AND ";
		       				$modified_query = $modified_query . " o.driverId = '" . $sOptions[$paramIndex + 1] . "'";
	        				$haveStatement = TRUE; 
        					break;
        				case 'container':
							if($haveStatement)
	        					$modified_query = $modified_query . " AND ";
		       				$modified_query = $modified_query . " oi.itemCode LIKE '" . $sOptions[$paramIndex + 1] . "'";
	        				$haveStatement = TRUE; 
        					break;
        			}        			
        			$paramIndex++; 		
        		}
        	}
    	}
    	if($frame == 'today'){
	    	if(strpos($modified_query, 'WHERE') == false)
	    		$modified_query = $modified_query . " WHERE ";
	    	else
	    		$modified_query = $modified_query . " AND ";
	    	$modified_query = $modified_query . " o.tripDate = '" . date("Y-m-d",mktime(0,0,0,date('m'),date("d"), date("Y"))) . "'";
    	}
	    if (strpos($modified_query,'ORDER BY') !== true) {
	    	$modified_query = $modified_query . ' ORDER BY o.tripDate DESC';
	    }
		return $this->get_final_list($modified_query);
    }
    
    public function get_activities_list($from, $to)
    {    
        //connect to database
        $this->db->connect();
    	        
        //prepare query
        $this->db->prepare
        (
            str_replace('{2}',$to,str_replace('{1}',$from,$this->queries->activities_query))
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
    
    public function get_transports_list()
    {    
        //connect to database
        $this->db->connect();
    	        
        //prepare query
        $this->db->prepare
        (
            $this->queries->transports_query
        );
        
        //execute query
        $this->db->query();
		$items = array();
		while($row = $this->db->fetchRows()) {
			$value = array
			(
				'id' => $row['id'],
				'providerId' => $row['providerId'],
				'providerName' => $row['providerName'],
				'isSubcontract' => $row['isSubcontract'],
				'driverId' => $row['driverId'],
				'driverName' => $row['driverName'],
				'code' => $row['code']
			);
						
			array_push($items, $value);
		}	
		return $items;
        
    }
    
    private function calculate_linked_orders($order)
    {
    	return '';
    }
    
    private function get_final_list($query)
    {
        //connect to database
        $this->db->connect();
        
        //prepare query
        $this->db->prepare
        (
            $query
        );
						
        //execute query
        $this->db->query();
    	
		$items = array();
		while($row = $this->db->fetchRows()) {
			$value = array
			(
				'id' => $row['id'],
				'itemId' => $row['itemId'],
				'code' => str_pad($row['code'],5,"0",STR_PAD_LEFT).'-'.$row['letter'],
				'statusId' => $row['status'],
				'status' => $row['statusName'],
				'tripDate' => $row['tripDate'],
				'clientId' => $row['clientId'],
				'client' => $row['clientName'],
				'fromPlace' => $row['fromPlace'],
				'toPlaceId' => $row['toPlaceId'],
				'toPlace' => $row['toPlace'],
				'driverId' => $row['driverId'],
				'driver' => $row['driverName'],
				'linked' => $this->calculate_linked_orders($row)
			);
						
			array_push($items, $value);
		}	
		return $items;	
    
    }
}