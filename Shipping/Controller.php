<?php
include("OdbcSqlData.php");

Class Controller{
	
	function start(){
		$action = "";
		if(isset($_GET["a"])){
			$action = $_GET["a"];
		}

		switch($action):
			case "vso":
				$this->showSO();
				break;
			case "sl":
				$this->showShippingList();
				break;
			case "Search":
				$this->combinedSearch();
				break;
			default:
				$this->showShipped();
				break;
			endswitch;
	}

	function showShipped(){
		$sqld = new SqlData();
		$start = 0;
		$end = 0;
		if(isset($_GET["start"])){
			$start = $_GET["start"];
		}
		if(isset($_GET["end"])){
			$end = $_GET["end"];
		}
		$result = $sqld->getBoxedItems($start, $end);
		$headers = array("Box ID", "Serial Number", "Part Number", "Sale Order", "so_line", "Date", 
			"Status", "ShipUID");
		$htmlTable = $this->createHTMLTable($result, $headers);
		$pagination = $this->getBoxPages(0, 0, "Boxes");

		include("ViewShipped.php");
	}

	function getBoxPages($start, $end, $entity){
		$sqld = new SqlData();

		$start = 0;
		$end = 0;
		if(isset($_GET["start"])){
			$start = $_GET["start"];
		}
		if(isset($_GET["end"])){
			$end = $_GET["end"];
		}

		$numberofRows = 0;
		if(!isset($_SESSION[$entity."Lines"])){
			$result = $sqld->getNumberofLines($start, $end, $entity);
			$row = odbc_fetch_row($result);
			$numberofRows = odbc_result($result, 1);
			$_SESSION[$entity."Lines"] = $numberofRows;
		}else{
			$numberofRows = $_SESSION[$entity."Lines"];
		}

		$rowsPerPage = 100;
		$numberofPages = $numberofRows/ $rowsPerPage;
		$currentPage = $start/100;

		$paginationPlug = "<a href=\"/Shipping\" >&nbsp;Page 1&nbsp;</a>";
		//$paginationPlug .= "<a href=\"?start=" .($start-200). "&end=" .($start-100). "\" >&nbsp;Page " .($currentPage-2). "&nbsp;</a>";
		//$paginationPlug .= "<a href=\"?start=" .$start-100. "&end=" .$start. "\" >&nbsp;Page ".$currentPage-2."&nbsp;</a>";
		$paginationPlug .= $currentPage;
		//$paginationPlug .= "<a href=\"?start=" .$start+100. "&end=" .$start+200. "\" >&nbsp;Page ".$currentPage+1."&nbsp;</a>";
		//$paginationPlug .= "<a href=\"?start=" .$start+200. "&end=" .$start+300. "\" >&nbsp;Page ".$currentPage+2."&nbsp;</a>";
		$paginationPlug .= "<a href=\"?start=".($numberofRows-100)."\">&nbsp;$numberofPages</a>";

		return $paginationPlug;
	}

	function returnPage($currentPage){
		///something
	}

	function createHTMLTable($result, $headers){
		$htmlTable = "<table style=\"border-width:0; \">";
		$htmlTable .= "<tr style=\"background-color:#4980B8; font-weight:bold; text-color:white;\">";
		foreach ($headers as $key => $value){
			$htmlTable .= "<td>$value</td>";
		}
		$htmlTable .= "</tr>";

		$counter = 1;
		//try{
			while($row = odbc_fetch_array($result)){
				$counter++;
				if($counter % 2){
					$htmlTable .= "<tr style=\"background-color:#5090B9;\">";
				}else{
					$htmlTable .= "<tr style=\"background-color:#;\">";
				}

				foreach ($row as $key => $value){
					switch(strtolower($key)):
						case "date":
							$htmlTable .= "<td>". substr($value, 0, 10) ."</td>";
							break;
						case "comments":
							$htmlTable .= "<td alt=\"$value\">". substr($value, 0, 25) ."</td>";
							break;
						case "so_number":
							//$htmlTable .= "<td><a href=\"?a=vso&p=$value\" >$value</a></td>";
							$htmlTable .= "<td>". $this->makeSoUrl($value) ."</td>";
							break;
						case "Customer_order":
							$htmlTable .= "<td>". $this->makeSoUrl($value) ."</td>";
							break;
						case "Customer_name":
							$htmlTable .= "<td>". $this->makeSoUrl($value) ."</td>";
							break;
						default:
							$htmlTable .= "<td>$value</td>";
							break;
					endswitch;
				}
				$htmlTable .= "</tr>";
			}
		//}catch(Exception $e){
		//	$e = $m*$c^2;
		//}

		$htmlTable .= "</table>";
		return $htmlTable;
	}

	function showShippingList(){
		$sqld = new SqlData();
		$start = 0;
		$end = 0;
		if(isset($_GET["start"])){
			$start = $_GET["start"];
		}
		if(isset($_GET["end"])){
			$end = $_GET["end"];
		}

		$result = $sqld->getShippingList($start, $end);
		$headers = array("Customer Name", "Customer Order", "Product Number", "Description",
			"Serial Number", "RMA Number", "Comments", "Date");
		$htmlTable = $this->createHTMLTable($result, $headers);
		$pagination = $this->getBoxPages(0, 0, "ShippingList");

		include("View-ShippingList.php");
	}

	function makeSoUrl($orderNum){
		//depricated function
		$rmaLink = "file:\\\legacy\prodscan\CO SHIPPING\RMA\ CO ";
		$link = "file:\\\legacy\prodscan\CO SHIPPING\CO ";
		switch(substr($orderNum, 0, 4)):
			case "0008":
				$link = $rmaLink . $orderNum;
				break;
			default:
				$link = $link . $orderNum;
				break;
		endswitch;
		//all code above is not neccesairy

		$formattedLink = "<a href=\"?a=vso&p=$orderNum\" >$orderNum</a>";
		//$formattedLink = "<a href=\"$link\" >$orderNum</a>";
		return $formattedLink;
	}

	function makeCustomerNameUrl($customer){

	}

	function showSO(){
		//$path = "/home/a8687159/public_html/pics/";
		//$path = getcwd();
		$so = "0000000000";
		if(isset($_GET["p"])){
			$so = $_GET["p"];
			//echo $so ." :: ". $_SESSION["p"];
		}
		include ("soModel.php");

		$sos = new SalesOrders();
		$htmlTable = $sos->getSOcontents($so);

		include ("soView.php");

		/*

		$randomFileName = rand(0, $counter-1);

		//echo implode(", ", $_SESSION["shownFiles"]);
		//echo "<br />";
		//echo "Shown files counter: " . $_SESSION["shownFilesCounter"];
		if($counter == (int)$_SESSION["shownFilesCounter"]){
			$_SESSION["shownFiles"] = array();
			$_SESSION["shownFilesCounter"] = 0;
		}

		while(isFileViewed($filesArray[$randomFileName])){
			$randomFileName = rand(0, $counter-1);		
		}

		//echo "SESSION[shownFiles]: " . implode(", ", $_SESSION["shownFiles"]);

		$ret = "<img src=\"/Pictures/". $filesArray[$randomFileName] ."\">";
		//$ret .= "<br />shownFilesCounter: " . $_SESSION["shownFilesCounter"] . "<br />counter: " . $counter;
		return $ret;

		*/


	}

	function combinedSearch(){
		$searchPar = $_GET["searchBox"];
		$pagination = "";
		if(strlen($searchPar) <= 4){
			$htmlTable = "Search parameters too short.";
			include ("ViewShipped.php");
			return;
		}

		$sqld = new SqlData();
		$result = $sqld->searchShipping($searchPar);
		$headers = array("Box ID", "Serial Number", "Part Number", "Sale Order", "so_line", "Date", 
			"Status", "ShipUID");
		$htmlTable = "&nbsp;<b>Packed Items:<b><br/>";
		$htmlTable .= $this->createHTMLTable($result, $headers);
		$htmlTable .= "<br/><br/>&nbsp;<b>Shipping List Items:<b><br/>";

		$result = $sqld->searchShippingList($searchPar);
		$headers = array("Customer Name", "Customer Order", "Product Number", "Description",
			"Serial Number", "RMA Number", "Comments", "Date");
		$htmlTable .= $this->createHTMLTable($result, $headers);
		//$pagination = $this->getBoxPages(0, 0, "ShippingList");

		include ("ViewShipped.php");
	}
}
?>