<?php
class Queries_Extension
{    
	
    public $list_query = "SELECT o.id, oi.id AS itemId, oi.code AS code, oi.letter, o.tripDate, o.clientId, 
    			o.fromPlaceId, o.toPlaceId, oi.status, st.name AS statusName, c.name AS clientName, p1.name AS fromPlace,
    			p1.placeType AS fromPlaceType, p2.placeType AS toPlaceType, 
				p2.name AS toPlace, d.name AS driverName
        		FROM orderItems oi 
				JOIN orders o ON o.id = oi.orderId
				JOIN statusTypes st ON st.id = oi.status
				JOIN clients c ON c.id = o.clientId
				JOIN places p1 ON p1.id = o.fromPlaceId
				JOIN places p2 ON p2.id = o.toPlaceId
				LEFT JOIN drivers d ON d.id = o.driverId";
    
    public $order_query = 'SELECT o.*, c.name AS clientName,
							p1.name AS fromPlace, p1.placeType AS fromPlaceType, 
							p2.name AS toPlace, p2.placeType AS toPlaceType, 
							d.name AS driverName, m.name AS movementName, 
							sc.name AS shippingName, ct.name AS containerName
    						FROM orders o
    						JOIN clients c ON c.id = o.clientId
							JOIN places p1 ON p1.id = o.fromPlaceId
							JOIN places p2 ON p2.id = o.toPlaceId
							LEFT JOIN drivers d ON d.id = o.driverId
							JOIN movementTypes m ON m.id = o.movementType
							LEFT JOIN shippingCompanies sc ON sc.id = o.shippingCo
							LEFT JOIN containerTypes ct ON ct.id = o.containerType	  
							WHERE o.id = %1$d';
    
    public $order_item_query = 'SELECT oi.*, ct.name AS containerName,  
    						d.name, i1.name AS infoTypeName1, i2.name AS infoTypeName2,i3.name AS infoTypeName3
						    FROM orderItems oi 
						    LEFT JOIN containerTypes ct ON ct.id = oi.containerType
                			LEFT JOIN drivers d ON d.id = oi.driverId
							LEFT JOIN infoTypes i1 ON i1.id = oi.infoType1
              				LEFT JOIN infoTypes i2 ON i2.id = oi.infoType2              
              				LEFT JOIN infoTypes i3 ON i3.id = oi.infoType3
						    WHERE oi.id = %1$d';
    
    public $order_activities_query = 'SELECT oa.*, a.name AS activityName, pt1.name AS fromPlaceType, pt2.name AS toPlaceType
    								   FROM orderActivities oa
    								   JOIN activities a ON a.id = oa.activityId
    								   JOIN placeTypes pt1 ON pt1.id = a.fromPlaceType
    								   JOIN placeTypes pt2 ON pt2.id = a.toPlaceType
    								   WHERE oa.orderItemId = %1$s';
    
    public $activities_query = 'SELECT a.* FROM activities a WHERE fromPlaceType = %1$d AND toPlaceType = %2$d';
    
    public $clients_query = 'SELECT c.* FROM clients c ORDER BY c.name';
    
    public $places_query = 'SELECT p.*, pt.name AS type FROM places p JOIN placeTypes pt ON pt.id = p.placeType';
    
    public $moveTypes_query = 'SELECT mt.* FROM movementTypes mt';
    
    public $infoTypes_query = 'SELECT it.* FROM infoTypes it WHERE it.section > 0 ORDER BY section';
    
    public $shippings_query = 'SELECT s.* FROM shippingCompanies s';
    
    public $containers_query = 'SELECT c.* FROM containerTypes c';
    
    public $statusTypes_query = 'SELECT st.* FROM statusTypes st';
    
    public $chassises_query = 'SELECT c.* FROM chassises c';
    
    public $transports_query = 'SELECT v.*, p.name AS providerName, p.isSubcontract AS isSubcontract, d.name AS driverName 
    							FROM vehicles v 
    							JOIN providers p ON p.id = v.providerId 
    							JOIN drivers d ON d.id = v.driverId';
    
    public $drivers_query = 'SELECT d.* FROM drivers d ORDER BY d.name';  

    public $currentcontainers_query = 'SELECT DISTINCT(oi.itemCode) AS name FROM orderItems oi WHERE oi.itemCode IS NOT NULL AND length(oi.itemCode) > 0 ORDER BY oi.itemCode';
    
    public function get_general_list($opt)
    {
    	$query = '';
        switch($opt){
        	case 'clients':
        		$query = $this->clients_query;
        		break;
        	case 'places':
        		$query = $this->places_query;
        		break;        		
        	case 'moveTypes':
        		$query = $this->moveTypes_query;
        		break;
        	case 'shippings':
        		$query = $this->shippings_query;
        		break;
        	case 'infoTypes':
        		$query = $this->infoTypes_query;
        		break;
        	case 'containers':
        		$query = $this->containers_query;
        		break;
        	case 'statusTypes':
        		$query = $this->statusTypes_query;
        		break;
        	case 'chassises':
        		$query = $this->chassises_query;
        		break;
        	case 'activities':
        		$query = $this->activities_query;
        		break;
        	case 'drivers':
        		$query = $this->drivers_query;
        		break;
        	case 'currentcontainers':
        		$query = $this->currentcontainers_query;
        		break;
        	default:
        		$query = $this->drivers_query;
        		break;
        }
        return $query;
    }
}