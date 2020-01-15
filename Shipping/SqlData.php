<?php
class SqlData{
	var $db;

	function getBoxedItems(){
		/*$SQL = "SELECT Shipping.boxid, Shipping.serial, Shipping.part_number, Shipping.qty, Shipping.so_number," + 
			" Shipping.so_line, Shipping.date, Shipping.status, Shipping.ShipUID, Shipping.PackUID  FROM" +
			" mwamos.dbo.Shipping as Shipping order by Shipping.date DESC";
		*/
		$SQL = "SELECT * FROM SHIPPING ORDER BY date DESC";
		$rows = $this->runSQL($SQL);
		return $rows;
	}

	function connect() {
		$hostname_server = "mwamos";
		$database_server = "mwamos";
		$username_server = "OMT";
		$password_server = "OMT98";

		//$this->db = mysql_connect($hostname_server, $username_server, $password_server)
		$this->db = mysql_connect($hostname_server, $username_server, $password_server) or die ("<html><script language='JavaScript'>alert('Unable to connect to database! Please try again later.'),history.go(-1)</script></html>");
			mysql_select_db($database_server, $this->db);
    	mysql_select_db ($database_server, $this->db) or die ("<html><script language='JavaScript'>alert('Unable to select database! Please try again later.'), history.go(-1)</script></html>");

    	return $this->db;
    }


	function runSQL($sqlStatement){
		
		$this->db = $this->connect();
		//if (PHP_VERSION < 6) {
    	//	$theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;}

    	/*$this->db = mysql_connect($hostname, $username, $password) or die ("<html><script language='JavaScript'>alert('Unable to connect to database! Please try again later.'),history.go(-1)</script></html>");
			mysql_select_db($dbname, $link);

		*/
		
		//$query = "SELECT * FROM CurrentList INNER JOIN products ON CurrentList.product_id = products.product_id WHERE CurrentList.product_id =" . $_POST["item_id"];
		$result = mysql_query($sqlStatement, $this->db);

		mysql_close();
		return $result;
    }
}

?>