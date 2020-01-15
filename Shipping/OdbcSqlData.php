<?php
include ("globals.php");
class SqlData{
	var $db;

	function getBoxedItems($start ="", $end = ""){

		$gl = new Globals();
		$resultConstraints = "TOP " . $gl->RowsPerPage;
		if($start != "" || $end != ""){
			$resultConstraints = "TOP $start FETCH NEXT $end"; 
		}

		$SQL = "SELECT $resultConstraints Shipping.boxid, Shipping.serial, Shipping.part_number, " .
		"Shipping.so_number, Shipping.so_line, Shipping.date, Shipping.status, Shipping.ShipUID FROM" .
		" mwamos.dbo.Shipping as Shipping order by Shipping.date DESC";
		
		//$SQL = "SELECT * FROM SHIPPING ORDER BY date DESC";
		$link = $this->connect();
		$rows = odbc_exec($link, $SQL);
		return $rows;
	}

	function getShippingList($start ="", $end = ""){

		$gl = new Globals();
		$resultConstraints = "TOP " . $gl->RowsPerPage;
		if($start != "" || $end != ""){
			$resultConstraints = "TOP $start FETCH NEXT $end"; 
		}

		$SQL = "SELECT $resultConstraints Customer_name, Customer_order, Product_number, Description, ".
		"Serial_number, RMA_number, Comments, Date from Daily_shipping order by Date DESC;";

		/*
		$SQL = "SELECT $resultConstraints MasterCustomerTable.id, Customer_name, Customer_order, Product_number, Description, ".
		"Serial_number, RMA_number, Comments, Date from Daily_shipping INNER JOIN Daily_shipping as DS ON DS.Customer_name = ".
		"MasterCustomerTable.Billing_Name order by DS.Date DESC;";
		*/

		//$SQL = "SELECT * FROM SHIPPING ORDER BY date DESC";
		$link = $this->connect();
		$result = odbc_exec($link, $SQL);
		return $result;
	}

	function getNumberofLines($start =0, $end = 0, $entity){

		$resultConstraints = "";
		if($start != 0 || $end != 0){
			$resultConstraints = "TOP $start FETCH NEXT $end"; 
		}

		$table = "";
		if($entity == "ShippingList"){
			$table = "Daily_shipping";
		}

		if($entity == "Boxes"){
			$table = "Shipping";
		}

		$SQL = "SELECT $resultConstraints COUNT(*) FROM $table;";
		$link = $this->connect();
		$rows = odbc_exec($link, $SQL);
		return $rows;
	}

	function searchShipping($searchPar = ""){
		$SQL = "SELECT boxid, serial, part_number, so_number, so_line, date, status, ShipUID
		FROM shipping WHERE boxid LIKE '%$searchPar%' OR serial LIKE '%$searchPar%' OR part_number LIKE '%$searchPar%'
		OR so_number LIKE '%$searchPar%' ORDER BY Date DESC";

		$link = $this->connect();
		$result = odbc_exec($link, $SQL);
		return $result;
	}

	function searchShippingList($searchPar = ""){
		$SQL = "SELECT Customer_name, Customer_order, Product_number, Description, Serial_number, RMA_number, Comments, Date
			FROM Daily_shipping WHERE Customer_name LIKE '%$searchPar%' OR Customer_order LIKE '%$searchPar%' OR Product_number LIKE '%$searchPar%' OR Serial_number LIKE '%$searchPar%' ORDER BY Date DESC";

		//echo $SQL;

		$link = $this->connect();
		$result = odbc_exec($link, $SQL);
		return $result;
	}

	function connect() {
		$hostname_server = "mwamos";
		$database_server = "mwamos";
		$username_server = "OMT";
		$password_server = "OMT98";

		$this->db = odbc_connect($hostname_server, $username_server, $password_server) or die ("<html>Unable to connect to database! Please try again later.</html>");
		//odbc select db

    	return $this->db;
    }

}

?>