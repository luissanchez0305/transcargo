<?php

class Create_Model {
	
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
}