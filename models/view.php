<?php
/**
 * Handles the view functionality of our MVC framework
 */
class View_Model
{
    private $db;
    private $queries;
	
    /**
     * Holds variables assigned to template
     */
    private $data = array();

    /**
     * Holds render status of view.
     */
    private $render = FALSE;

    /**
     * Accept a template to load
     */
    public function __construct($template)
    {
        $this->db = new MysqlImproved_Driver;
        $this->queries = new Queries_Extension;
        
        //compose file name
        $file = SERVER_ROOT . '/views/' . strtolower($template) . '.php';
    
        if (file_exists($file))
        {
            /**
             * trigger render to include file when this model is destroyed
             * if we render it now, we wouldn't be able to assign variables
             * to the view!
             */
            $this->render = $file;
        }       
    }
    
    /**
     * Receives assignments from controller and stores in local data array
     * 
     * @param $variable
     * @param $value
     */
    public function assign($variable , $value)
    {
        $this->data[$variable] = $value;
    }

    /**
     * Render the output directly to the page, or optionally, return the
     * generated output to caller.
     * 
     * @param $direct_output Set to any non-TRUE value to have the 
     * output returned rather than displayed directly.
     */
    public function render($direct_output = TRUE)
    {
        // Turn output buffering on, capturing all output
        if ($direct_output !== TRUE)
        {
            ob_start();
        }

        // Parse data variables into local variables
        $data = $this->data;
    
        // Get template
        include($this->render);
        
        // Get the contents of the buffer and return it
        if ($direct_output !== TRUE)
        {
            return ob_get_clean();
        }
    }

    public function __destruct()
    {
    }
    
    public function get_general_list($option)
    {
        //connect to database
        $this->db->connect();
        
        //prepare query
        $this->db->prepare
        (
            $this->queries->get_general_list($option)
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
    
    public function get_infoTypes()
    {
        //connect to database
        $this->db->connect();
    
        //prepare query
        $this->db->prepare
        (
            $this->queries->infoTypes_query
        );
    	
        //execute query
        $this->db->query();

		$items = array();
		while($row = $this->db->fetchRows()) {
			$value = array
			(
				'id' => $row['id'],
				'name' => $row['name'],
				'section' => $row['section']
			);
						
			array_push($items, $value);
		}	
		return $items;     
    }
    
    public function get_places()
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
    
    public function get_vehicle($id)
    {
        //connect to database
        $this->db->connect();
        
	    //sanitize data
	    //$id = $this->db->escape($id);
	    
        //prepare query
        $this->db->prepare
        (
            $this->queries->transports_query.' WHERE v.id = '.$id
        );
						
        //execute query
        $this->db->query();
    	
        $row = $this->db->fetchRow('array');
        
        return array
        (
        	'id' => $row[0],
        	'driverId' => $row[2],
        	'code' => $row[3],
        	'providerId' => $row[4],
        	'providerName' => $row[5],
        	'isSubcontract' => $row[6],
        	'driverName' => $row[7]       	
        );    	
    }
    
    public function get_place($id)
    {
        //connect to database
        $this->db->connect();
        
	    //sanitize data
	    //$id = $this->db->escape($id);
	    
        //prepare query
        $this->db->prepare
        (
            $this->queries->places_query.' WHERE id = '.$id
        );
						
        //execute query
        $this->db->query();
    	
        $row = $this->db->fetchRow('array');
        
        return array
        (
        	'id' => $row[0],
        	'placeType' => $row[1],
        	'name' => $row[2]
        );
    }
}