<?php
/**
 * The MySQL Improved driver extends the Database_Library to provide 
 * interaction with a MySQL database
 */
class MysqlImproved_Driver extends Database_Library
{
    /**
     * Connection holds MySQLi resource
     */
    private $connection;

    /**
     * Query to perform
     */
    private $query;

    /**
     * Result holds data retrieved from server
     */
    private $result;

    /**
     * Create new connection to database
     */ 
    public function connect()
    {
        //connection parameters
        $host = 'localhost';
        $user = 'espheras_dbuser';
        $password = 'Goingup123';
        $database = 'espheras_transcargo';

        //your implementation may require these...
        $port = 3306;
        $socket = NULL;    
    
        //create new mysqli connection
        $this->connection = new mysqli
        (
            $host , $user , $password , $database
        );
        return TRUE;
    }

    public function disconnect()
    {        
    	//clean up connection!
        $this->connection->close();    
    
        return TRUE;
    }

    public function prepare($query)
    {    
        //store query in query variable
        $this->query = $query;    
    
        return TRUE;
    }

    public function query()
    {
    	$result = FALSE;
        if (isset($this->query))
        {
            //execute prepared query and store in result variable
            $this->result = $this->connection->query($this->query);
   			$result = TRUE;
        }
        return $result;
    }
    
    public function getLastId()
    {
    	return mysqli_insert_id($this->connection);
    }


    /**
     * Fetch a row from the query result
     * 
     * @param $type
     */
    public function fetchRow($type = 'object')
    {
        if (isset($this->result))
        {
            switch ($type)
            {
                case 'array':
            
                    //fetch a row as array
                    $row = $this->result->fetch_array();
            
                break;
            
                case 'assoc':
            
                 	$row = mysql_fetch_assoc($this->result);
                 	
                break;
            
                default:
                
                    //fetch a row as object
                    $row = $this->result->fetch_object();    
                    
                break;
            }
        
            return $row;
        }
    
        return FALSE;
    }
    
    public function fetchRows()
    {
        if (isset($this->result))
        {
        	return $this->result->fetch_assoc();
        }
        
        return FALSE;    	
    }
    
    /**
	 * Sanitize data to be used in a query
	 * 
	 * @param $data
	 */
	public function escape($data)
	{
	    return $this->connection->real_escape_string($data);
	}
}