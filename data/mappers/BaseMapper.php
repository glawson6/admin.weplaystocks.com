<?php

class BaseMapper {
    
//    private $dataBaseUserName = "root";
//    private $dataBasePassword="root";
//    private $dataBaseServerName="INSHARP03";
//    private $database="trading_simulation";
    
//    private $dataBaseUserName = "sts_admin1";
//    private $dataBasePassword="passw0rd#";
//    private $dataBaseServerName="cashmobnetworknet.ipagemysql.com";
//    private $database="sts_db";
	
//	private $dataBaseUserName = "stocktradecffl";
//    private $dataBasePassword="b@tisteR0d";
//    private $dataBaseServerName="stocktradecffl.db.2684880.hostedresource.com";
//    private $database="stocktradecffl";
// openshift
//	private $dataBaseUserName = "admin6h99x4M";
//    private $dataBasePassword="rzeAD7KAYIhR";
//    private $dataBaseServerName="";
//    private $database="php";

    private $dataBaseUserName = "admin";
    private $dataBasePassword = "UVjc5Aa8onuGDRZs";
    private $dataBaseServerName = "172.17.0.35";
    private $database = "db";

    
//    private $dataBaseUserName = "insharp6_trading";
//    private $dataBasePassword = "ee;yUT1pylRr";
//    private $dataBaseServerName = "localhost";
//    private $database = "insharp6_trading";

    protected $connection;

    //create the connection to the database
    public function OpenMySqlConnection() {
//    $this->host = getenv("OPENSHIFT_MYSQL_DB_HOST");
//        $this->connection = mysql_connect($this->host, $this->dataBaseUserName, $this->dataBasePassword)
//                or die('Could not connect to the Data Base');


        $this->connection = mysql_connect($this->dataBaseServerName, $this->dataBaseUserName, $this->dataBasePassword)
                or die('Could not connect to the Data Base');

        mysql_select_db($this->database, $this->connection)
                or die('Could not Select the Data Base');
    }

    //close the database connection
    public function CloseMySqlConnection() {
        mysql_close($this->connection);
    }

    // format a date for use in SQL
    public function EscapeDate($date) {
        return date('Y-m-d', strtotime($date));
    }

    // format a date for use in HTML
    public function UnescapeDate($date) {
        return date('m/d/Y', strtotime($date));
    }

}

?>
